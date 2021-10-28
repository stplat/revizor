<?php

namespace App\Services;

use App\Repositories\BoxRecognitionRepository;
use App\Repositories\UikRepository;

use App\Models\BoxesInfo;
use App\Models\BoxRecognition;
use App\Models\Camera;
use App\Models\Constant;
use App\Models\UikLabel;
use App\Models\Violation;
use App\Models\ViolationImage;
use App\Models\Check;
use App\Models\UikTiming;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RecognitionService
{
    /**
     * @var BoxRecognitionRepository
     */
    protected $boxRecognition;

    /**
     * @var UikRepository
     */
    protected $uikRepository;

    /**
     * RecognitionService constructor.
     */
    public function __construct()
    {
        $this->boxRecognition = app(BoxRecognitionRepository::class);
        $this->uikRepository = app(UikRepository::class);
    }

    /**
     * Получаем данные для выпадающих списков с параметрами выгрузки распознаваний
     * @return \Illuminate\Support\Collection
     */
    public function getFilterList(): \Illuminate\Support\Collection
    {
        $checkId = Check::where('active', true)->first()->check_id;
        $uikList = $this->uikRepository->findUiks($checkId);

        return collect([
            'selectCameraCount' => $this->boxRecognition->findRecognitionsForSelectCameraCount($checkId),
            'checkBoxesCount' => $this->boxRecognition->findRecognitionsForCheckBoxesCount($checkId),
            'uikList' => $uikList->map(function ($item) {
                return collect([
                    'id' => $item->id,
                    'name' => "Участок №$item->number",
                ]);
            }),
            'regionList' => $uikList->groupBy('region_number')->map(function ($item) {
                return collect([
                    'id' => $item[0]->region_number,
                    'name' => $item[0]->region_number . " - " . $item[0]->region,
                ]);
            })->values()
        ]);
    }

    /**
     * Получаем участки данные для отчета
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function showReport(object $request): \Illuminate\Support\Collection
    {
        $checkId = Check::where('active', true)->first()->check_id;
        $collect = collect($request)->put('checkId', $checkId);
        $result = [];
        $count = 0;

        if ($request['recognitionIds'] && count($request['recognitionIds'])) {
            BoxRecognition::whereIn('recognition_id', $request['recognitionIds'])->update([
                'checking' => false
            ]);
        }

        if ($request['type'] === '1') {
            $result = $this->boxRecognition->findRecognitionsForSelectCamera($collect);
            $recognitionIds = collect();

            foreach ($result as $uik) {
                foreach ($uik['recognitions'] as $recognition) {
                    foreach ($recognition['cameras'] as $camera) {
                        $recognitionIds->push($camera['recognition_id']);
                    }
                }
            }

            $count = $this->boxRecognition->findRecognitionsForSelectCameraCount($checkId);
        } else if ($request['type'] === '2') {
            $result = $this->boxRecognition->findRecognitionsForCheckBoxes($collect);
            $recognitionIds = $result->reduce(function ($carry, $item) {
                return $carry->push($item['camera']['recognition_id']);
            }, collect());

            $count = $this->boxRecognition->findRecognitionsForCheckBoxesCount($checkId);
        }

        BoxRecognition::whereIn('recognition_id', $recognitionIds)->update([
            'checking' => true
        ]);

        return collect([
            'recognitionIds' => $recognitionIds,
            'report' => $result,
            'count' => $count
        ]);
    }

    /**
     * Меняем статус (checking) у выгруженных распознаваний
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function updateCheckingRecognition(object $request)
    {
        BoxRecognition::whereIn('recognition_id', $request->recognitions)->update([
            'checking' => false
        ]);
    }

    /**
     * Получаем предыдущее распознавание
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function getPrevRecognition(object $request)
    {
        return $this->boxRecognition->findPrevRecognition($request->recognition, $request->camera);
    }

    /**
     * Редактируем, добавляем, удаляем ящик(и)
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function updateBoxes($request)
    {
        $checkId = Check::where('active', true)->first()->check_id;
        $collect = collect($request)->put('checkId', $checkId)->merge($request->params);
        $deletedBoxes = collect($request->deletedBoxes);
        $boxes = collect($request->boxes);
        $recognitions = collect($request->recognitions);
        $constant = Constant::first();

        DB::transaction(function () use ($deletedBoxes, $boxes, $recognitions, $request) {
            if (count($deletedBoxes)) {
                BoxesInfo::destroy($deletedBoxes);
            }

            $boxes->each(function ($item) {
                if (is_integer($item['box_id'])) {
                    $boxesInfo = BoxesInfo::where('box_id', $item['box_id'])->first();

                    if ($boxesInfo->type != $item['type']) {
                        $boxesInfo->type = $item['type'];
                        $boxesInfo->type_conf = 1.0;
                    }

                    if ($boxesInfo->box_bbox_coords != $item['box_bbox_coords']) {
                        $boxesInfo->box_quality = $item['box_quality'];
                        $boxesInfo->conf = 1.0;
                        $boxesInfo->normalized_width_k = $item['normalized_width_k'];
                        $boxesInfo->normalized_dist_k = $item['normalized_dist_k'];
                        $boxesInfo->centroid_k = $item['centroid_k'];
                        $boxesInfo->box_bbox_coords = $item['box_bbox_coords'];
                    }

                    if ($boxesInfo->cap_type != $item['cap_type']) {
                        $boxesInfo->cap_type = $item['cap_type'];
                    }

                    if ($boxesInfo->cap_type == 'poly' && $boxesInfo->cap_rot_bbox != $item['cap_rot_bbox']) {
                        $boxesInfo->cap_rot_bbox = $item['cap_rot_bbox'];
                        $boxesInfo->cap_centroid_k = $item['cap_centroid_k'];
                    }

                    if ($boxesInfo->cap_type == 'bbox' && $boxesInfo->cap_ort_bbox != $item['cap_ort_bbox']) {
                        $boxesInfo->cap_ort_bbox = $item['cap_ort_bbox'];
                        $boxesInfo->cap_centroid_k = $item['cap_centroid_k'];
                    }

                    $boxesInfo->save();
                } else if (Str::contains($item['box_id'], 'temp')) {
                    $boxNum = BoxesInfo::where('recognition_id', $item['recognition_id'])->orderBy('box_num', 'DESC')->first();
                    $boxesInfo = new BoxesInfo();
                    $boxesInfo->recognition_id = $item['recognition_id'];
                    $boxesInfo->box_num = $boxNum ? $boxNum->box_num + 1 : 1;
                    $boxesInfo->box_quality = $item['box_quality'];
                    $boxesInfo->conf = $item['conf'];
                    $boxesInfo->type = $item['type'];
                    $boxesInfo->type_conf = $item['type_conf'];
                    $boxesInfo->normalized_dist_k = $item['normalized_dist_k'];
                    $boxesInfo->normalized_width_k = $item['normalized_width_k'];
                    $boxesInfo->box_bbox_coords = $item['box_bbox_coords'];
                    $boxesInfo->centroid_k = $item['centroid_k'];
                    $boxesInfo->cap_type = $item['cap_type'];
                    $boxesInfo->cap_rot_bbox = $item['cap_rot_bbox'];
                    $boxesInfo->cap_centroid_k = $item['cap_centroid_k'];
                    $boxesInfo->save();
                } else if (Str::contains($item['box_id'], 'clone')) {
                    $boxNum = BoxesInfo::where('recognition_id', $item['recognition_id'])->orderBy('box_num', 'DESC')->first();
                    $boxId = Str::afterLast($item['box_id'], 'clone-');
                    $boxesInfoDonor = BoxesInfo::find($boxId);

                    $boxesInfo = $boxesInfoDonor->replicate();
                    $boxesInfo->recognition_id = $item['recognition_id'];
                    $boxesInfo->box_num = $boxNum ? $boxNum->box_num + 1 : 1;
                    $boxesInfo->box_quality = $item['box_quality'];
                    $boxesInfo->conf = $item['conf'];
                    $boxesInfo->type = $item['type'];
                    $boxesInfo->type_conf = $item['type_conf'];
                    $boxesInfo->normalized_dist_k = $item['normalized_dist_k'];
                    $boxesInfo->normalized_width_k = $item['normalized_width_k'];
                    $boxesInfo->box_bbox_coords = $item['box_bbox_coords'];
                    $boxesInfo->centroid_k = $item['centroid_k'];
                    $boxesInfo->cap_type = $item['cap_type'];
                    $boxesInfo->cap_rot_bbox = $item['cap_rot_bbox'];
                    $boxesInfo->cap_ort_bbox = $item['cap_ort_bbox'];
                    $boxesInfo->cap_centroid_k = $item['cap_centroid_k'];
                    $boxesInfo->save();
                }
            });

            $boxFlagUiks = $recognitions->reduce(function ($carry, $item) {
                if (!in_array($item['uik_id'], $carry) && $item['boxes_flag']) {
                    array_push($carry, $item['uik_id']);
                }

                return $carry;
            }, []);

            $violationUiks = [];

            foreach ($recognitions as $item) {
                $boxRecognition = BoxRecognition::where('recognition_id', $item['id'])->first();
                $boxRecognition->boxes_flag = $item['boxes_flag'];
                $boxRecognition->cam_quality = $item['cam_quality'];
                $boxRecognition->boxes_num = $item['boxes_num'];
                $boxRecognition->checking = false;
                $boxRecognition->checked = true;
                $boxRecognition->checked_by = Auth::user()->user_id;
                $boxRecognition->check_datetime = now()->format('Y-m-d H:i:s');
                $boxRecognition->save();

                foreach ($item['cameras'] as $camera) {
                    UikTiming::get()->each(function ($timing) use ($camera) {

                        if ($timing['8h'] == $camera['video_id']) {
                            $timing->update([
                                'approved_8h' => $camera['recognition_id']
                            ]);
                        }

                        if ($timing['10h'] == $camera['video_id']) {
                            $timing->update([
                                'approved_10h' => $camera['recognition_id']
                            ]);
                        }

                        if ($timing['12h'] == $camera['video_id']) {
                            $timing->update([
                                'approved_12h' => $camera['recognition_id']
                            ]);
                        }

                        if ($timing['14h'] == $camera['video_id']) {
                            $timing->update([
                                'approved_14h' => $camera['recognition_id']
                            ]);
                        }

                        if ($timing['16h'] == $camera['video_id']) {
                            $timing->update([
                                'approved_16h' => $camera['recognition_id']
                            ]);
                        }

                        if ($timing['18h'] == $camera['video_id']) {
                            $timing->update([
                                'approved_18h' => $camera['recognition_id']
                            ]);
                        }
                    });

                    if ($request->params['type'] == '1') {
                        Camera::where('cam_numeric_id', $camera['id'])->update([
                            'main' => $camera['countable'],
                        ]);
                    }
                }

                if (in_array($item['uik_id'], $boxFlagUiks)) {
                    $violation = new Violation();

                    if (!in_array($item['uik_id'], $violationUiks)) {

                        if (!$item['countable']) {
                            $violation->cam_numeric_id = count($item['cameras']) ? $item['cameras'][0]['id'] : null;
                            $violation->violation_type_id = 4;
                            $violation->creation_datetime = now()->format('Y-m-d H:i:s');
                            $violation->violation_datetime_start = $item['recognition_datetime'];
                            $violation->status_id = 1;
                            $violation->save();

                            foreach ($item['cameras'] as $camera) {
                                $violationImages = new ViolationImage();
                                $violationImages->violation_id = $violation->violation_id;
                                $violationImages->image_id = $camera['image']['image_id'];
                                $violationImages->save();
                            }

                            array_push($violationUiks, $item['uik_id']);
                        }
                    }
                } else {
                    $violation = new Violation();

                    if (!in_array($item['uik_id'], $violationUiks)) {
                        $violation->cam_numeric_id = count($item['cameras']) ? $item['cameras'][0]['id'] : null;
                        $violation->violation_type_id = 5;
                        $violation->creation_datetime = now()->format('Y-m-d H:i:s');
                        $violation->violation_datetime_start = $item['recognition_datetime'];
                        $violation->status_id = 1;
                        $violation->save();
                    }

                    foreach ($item['cameras'] as $cameraItem) {

                        if ($violation->violation_id) {
                            $violationImages = new ViolationImage();
                            $violationImages->violation_id = $violation->violation_id;
                            $violationImages->image_id = $cameraItem['image']['image_id'];
                            $violationImages->save();
                        }
                    }

                    $uikLabel = UikLabel::firstOrCreate(
                        [
                            'uik_id' => $item['uik_id'],
                            'label_id' => 3
                        ],
                        [
                            'uik_id' => $item['uik_id'],
                            'label_id' => 3
                        ]
                    );

                    $uikLabel->save();

                    array_push($violationUiks, $item['uik_id']);
                }
            }
        });

        return $this->showReport($collect);
    }
}
