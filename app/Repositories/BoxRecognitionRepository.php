<?php

namespace App\Repositories;

use App\Models\BoxRecognition as Model;

use Jenssegers\Date\Date;

/**
 * Class BoxRecognitionRepository
 * @package App\Repositories
 */
class BoxRecognitionRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем данные по распознаванию для подсказки
     * @param int $imageId
     */
    public function findRecognitionForTip($imageId): \Illuminate\Support\Collection
    {
        $columns = [
            'box_recognitions.recognition_id as id',
            'box_recognitions.checked',
            'box_recognitions.check_datetime',
            'images.datetime as date',
            'boxes_info.type',
            'boxes_info.normalized_width_k',
            'users.username',
        ];

        $model = $this->startCondition()
            ->select($columns)
            ->join('box_recognitions_images', 'box_recognitions.recognition_id', '=', 'box_recognitions_images.recognition_id')
            ->join('images', 'box_recognitions_images.image_id', '=', 'images.image_id')
            ->join('boxes_info', 'box_recognitions.recognition_id', '=', 'boxes_info.recognition_id')
            ->join('users', 'box_recognitions.checked_by', '=', 'user_id')
            ->where('box_recognitions_images.image_id', $imageId)
            ->get()
            ->groupBy('id')
            ->map(function ($item) {
                return collect([
                    'id' => $item[0]->id,
                    'imageDate' => $item[0]->date,
                    'checkDate' => $item[0]->check_datetime,
                    'boxCount' => $item->count(),
                    'boxTypes' => $item->reduce(function ($carry, $item) {
                        in_array($item->type, $carry) ?: array_push($carry, $item->type);
                        return $carry;
                    }, []),
                    'coefficient' => $item->reduce(function ($carry, $item) {
                        return $carry > $item->normalized_width_k ? $item->normalized_width_k : $carry;
                    }, 1),
                    'checked' => $item[0]->checked,
                    'username' => $item[0]->username
                ]);
            })->values();

        return $model->count() ? $model[0] : collect();
    }


    /**
     * Поиск количества непроверенных распознаваниями (отчет - "Выбор камеры")
     * @param int $checkId
     */
    public function findRecognitionsForSelectCameraCount($checkId)
    {
        $columns = [
            'box_recognitions.recognition_id',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('videos_processing', 'box_recognitions.video_id', '=', 'videos_processing.video_id')
            ->leftJoin('uiks', 'box_recognitions.uik_id', '=', 'uiks.uik_id')
            ->where('box_recognitions.checked', false)
            ->where('box_recognitions.checking', false)
            ->where('task_type', 'selecting_cam')
            ->where('uiks.check_id', $checkId)
            ->count();
    }

    /**
     * Поиск количества непроверенных распознаваний (отчет - "Проверка ящиков")
     * @param int $checkId
     */
    public function findRecognitionsForCheckBoxesCount($checkId)
    {
        $columns = [
            'box_recognitions.recognition_id',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('videos_processing', 'box_recognitions.video_id', '=', 'videos_processing.video_id')
            ->leftJoin('uiks', 'box_recognitions.uik_id', '=', 'uiks.uik_id')
            ->where('box_recognitions.checked', false)
            ->where('box_recognitions.checking', false)
            ->where('task_type', 'boxes')
            ->where('uiks.check_id', $checkId)
            ->count();
    }

    /**
     * Поиск непроверенных распознаваний сгруппированных по участкам (отчет - "Выбор камеры")
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function findRecognitionsForSelectCamera(object $request): \Illuminate\Support\Collection
    {
        $columns = [
            'cameras.cam_numeric_id',
            'cameras.cam_num',
            'cameras.cam_id',
            'cameras.main',
            'images.image_id',
            'images.raw_image',
            'images.width',
            'images.height',
            'images.datetime',
            'boxes_info.box_id',
            'boxes_info.type',
            'boxes_info.box_bbox_coords',
            'boxes_info.box_quality',
            'boxes_info.cap_type',
            'boxes_info.cap_ort_bbox',
            'boxes_info.cap_rot_bbox',
            'boxes_info.normalized_width_k',
            'uiks.uik_id',
            'uiks.uik_number',
            'uiks.region_number',
            'uiks.address',
            'uiks.timezone_offset',
            'box_recognitions.recognition_id',
            'box_recognitions.cam_quality',
            'box_recognitions.boxes_num',
            'box_recognitions.boxes_flag',
            'box_recognitions.checked',
            'box_recognitions.check_datetime',
            'box_recognitions.recognition_datetime',
            'box_recognitions.video_id',

        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('videos_processing', 'box_recognitions.video_id', '=', 'videos_processing.video_id')
            ->leftJoin('box_recognitions_images', 'box_recognitions.recognition_id', '=', 'box_recognitions_images.recognition_id')
            ->leftJoin('images', 'box_recognitions_images.image_id', '=', 'images.image_id')
            ->leftJoin('boxes_info', 'box_recognitions.recognition_id', '=', 'boxes_info.recognition_id')
            ->leftJoin('cameras', 'box_recognitions.cam_numeric_id', '=', 'cameras.cam_numeric_id')
            ->leftJoin('uiks', 'cameras.uik_id', '=', 'uiks.uik_id')
            ->where('box_recognitions.checked', false)
            ->where('box_recognitions.checking', false)
            ->where('task_type', 'selecting_cam')
            ->where('uiks.check_id', $request['checkId'])
            ->whereIn('uiks.uik_id', $request['uiks'])
            ->whereIn('uiks.region_number', $request['regions'])
            ->orderBy('recognition_id')
            ->get()
            ->groupBy('uik_id')
            ->map(function ($item) {
                $activeCamera = $item->reduce(function ($carry, $item) {
                    if (!$carry->count() && $item->box_id && $item->normalized_width_k) return $item;

                    return $item->normalized_width_k && $item->box_id && $item->normalized_width_k > $carry->normalized_width_k ? $item : $carry;
                }, collect());

                $activeCamera = $activeCamera->count() ? $activeCamera->cam_numeric_id : null;

                return collect([
                    'uik_id' => $item[0]->uik_id,
                    'name' => 'Регион ' . $item[0]->region_number . ', УИК ' . $item[0]->uik_number,
                    'address' => $item[0]->address,
                    'countable' => !!$item->where('main', true)->count(),
                    'date' => now()->parse(strtotime(now()) + $item[0]->timezone_offset * 60),
                    'cameras' => $item->groupBy('cam_numeric_id')->map(function ($item) {
                        return collect([
                            'id' => $item[0]->cam_numeric_id,
                            'number' => $item[0]->cam_num,
                            'code' => $item[0]->cam_id,
                            'countable' => $item[0]->main,
                        ]);
                    })->values(),
                    'recognitionCount' => $item->groupBy('recognition_id')->count(),
                    'recognitions' => $item->map(function ($item) {
                        return collect($item)->put('recognition_datetime_parse', Date::parse($item->recognition_datetime)->format('j F Yг.'));
                    })->groupBy('recognition_datetime_parse')->map(function ($recognition, $key) use ($activeCamera) {
                        return collect([
                            'id' => $recognition[0]['recognition_id'],
                            'date' => $key,
                            'recognition_datetime' => $recognition[0]['recognition_datetime'],
                            'cam_quality' => $recognition[0]['cam_quality'],
                            'boxes_num' => $recognition[0]['boxes_num'],
                            'boxes_flag' => $recognition[0]['boxes_flag'],
                            'cameras' => $recognition->groupBy('cam_numeric_id')->map(function ($item) use ($activeCamera) {
                                return collect([
                                    'id' => $item[0]['cam_numeric_id'],
                                    'recognition_id' => $item[0]['recognition_id'],
                                    'video_id' => $item[0]['video_id'],
                                    'number' => $item[0]['cam_num'],
                                    'countable' => $activeCamera == $item[0]['cam_numeric_id'],
                                    'boxTypes' => $item->pluck('type')->filter(function ($item) {
                                        return !!$item;
                                    })->unique()->values(),
                                    'image' => $item->groupBy('recognition_id')->values()->map(function ($item) {
                                        return collect([
                                            "image_id" => $item[0]['image_id'],
                                            "raw_image" => asset($item[0]['raw_image']),
                                            "width" => $item[0]['width'],
                                            "height" => $item[0]['height'],
                                            "datetime" => $item[0]['datetime'],
                                            "boxes" => $item->map(function ($item) {
                                                return collect([
                                                    'box_id' => $item['box_id'],
                                                    'type' => $item['type'],
                                                    'box_bbox_coords' => $item['box_bbox_coords'],
                                                    'box_quality' => $item['box_quality'],
                                                    'cap_type' => $item['cap_type'],
                                                    'cap_ort_bbox' => $item['cap_ort_bbox'],
                                                    'cap_rot_bbox' => $item['cap_rot_bbox'],
                                                    'normalized_width_k' => $item['normalized_width_k'],
                                                ]);
                                            })->filter(function ($item) {
                                                return $item['box_id'];
                                            })->values(),
                                        ]);
                                    })->first()
                                ]);
                            })->values(),
                        ]);
                    })->values()
                ]);
            })->values()
            ->take($request['limit']);
    }

    /**
     * Поиск непроверенных распознаваний (отчет - "Проверка ящиков")
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function findRecognitionsForCheckBoxes(object $request): \Illuminate\Support\Collection
    {
        $columns = [
            'cameras.cam_numeric_id',
            'cameras.cam_num',
            'cameras.cam_id',
            'cameras.main',
            'images.image_id',
            'images.raw_image',
            'images.width',
            'images.height',
            'images.datetime',
            'boxes_info.box_id',
            'boxes_info.type',
            'boxes_info.box_bbox_coords',
            'boxes_info.box_quality',
            'boxes_info.conf',
            'boxes_info.cap_type',
            'boxes_info.cap_ort_bbox',
            'boxes_info.cap_rot_bbox',
            'boxes_info.normalized_width_k',
            'uiks.uik_id',
            'uiks.uik_number',
            'uiks.region_number',
            'uiks.address',
            'uiks.timezone_offset',
            'box_recognitions.recognition_id',
            'box_recognitions.cam_quality',
            'box_recognitions.boxes_num',
            'box_recognitions.boxes_flag',
            'box_recognitions.checked',
            'box_recognitions.check_datetime',
            'box_recognitions.recognition_datetime',
            'box_recognitions.video_id',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('videos_processing', 'box_recognitions.video_id', '=', 'videos_processing.video_id')
            ->leftJoin('box_recognitions_images', 'box_recognitions.recognition_id', '=', 'box_recognitions_images.recognition_id')
            ->leftJoin('images', 'box_recognitions_images.image_id', '=', 'images.image_id')
            ->leftJoin('boxes_info', 'box_recognitions.recognition_id', '=', 'boxes_info.recognition_id')
            ->leftJoin('cameras', 'box_recognitions.cam_numeric_id', '=', 'cameras.cam_numeric_id')
            ->leftJoin('uiks', 'cameras.uik_id', '=', 'uiks.uik_id')
            ->where('box_recognitions.checked', false)
            ->where('box_recognitions.checking', false)
            ->where('task_type', 'boxes')
            ->where('uiks.check_id', $request['checkId'])
            ->whereIn('uiks.uik_id', $request['uiks'])
            ->whereIn('uiks.region_number', $request['regions'])
            ->orderBy('box_recognitions.recognition_id')
            ->get()
            ->groupBy('recognition_id')
            ->map(function ($item) {
                $activeCamera = $item->reduce(function ($carry, $item) {
                    if (!$carry->count() && $item->box_id && $item->normalized_width_k) return $item;

                    return $item->normalized_width_k && $item->box_id && $item->normalized_width_k > $carry->normalized_width_k ? $item : $carry;
                }, collect());

                $activeCamera = $activeCamera->count() ? $activeCamera->cam_numeric_id : null;

                return collect([
                    'cam_quality' => $item[0]->cam_quality,
                    'boxes_num' => $item[0]->boxes_num,
                    'boxes_flag' => $item[0]->boxes_flag,
                    'recognition_datetime' => $item[0]->recognition_datetime,
                    'uik' => collect([
                        'uik_id' => $item[0]->uik_id,
                        'name' => 'Регион ' . $item[0]->region_number . ', УИК ' . $item[0]->uik_number,
                        'address' => $item[0]->address,
                        'countable' => $item[0]->main,
                        'date' => now()->parse(strtotime(now()) + $item[0]->timezone_offset * 60),
                        'cameras' => $item->map(function ($item) {
                            return collect([
                                'id' => $item->cam_numeric_id,
                                'number' => $item->cam_num,
                                'code' => $item->cam_id,
                                'countable' => $item->main,
                            ]);
                        })->values(),
                    ]),
                    'camera' => collect([
                        'id' => $item[0]->cam_numeric_id,
                        'recognition_id' => $item[0]->recognition_id,
                        'video_id' => $item[0]->video_id,
                        'number' => $item[0]->cam_num,
                        'countable' => $activeCamera == $item[0]->cam_numeric_id,
                        'boxTypes' => $item->pluck('type')->filter(function ($item) {
                            return !!$item;
                        })->unique()->values(),
                        'image' => collect([
                            "image_id" => $item[0]['image_id'],
                            "raw_image" => asset($item[0]['raw_image']),
                            "width" => $item[0]['width'],
                            "height" => $item[0]['height'],
                            "datetime" => $item[0]['datetime'],
                            "boxes" => $item->map(function ($item) {
                                return collect([
                                    'box_id' => $item['box_id'],
                                    'type' => $item['type'],
                                    'box_bbox_coords' => $item['box_bbox_coords'],
                                    'box_quality' => $item['box_quality'],
                                    'conf' => $item['conf'],
                                    'cap_type' => $item['cap_type'],
                                    'cap_ort_bbox' => $item['cap_ort_bbox'],
                                    'cap_rot_bbox' => $item['cap_rot_bbox'],
                                    'normalized_width_k' => $item['normalized_width_k'],
                                ]);
                            })->filter(function ($item) {
                                return $item['box_id'];
                            })->values(),
                        ])
                    ])
                ]);
            })->values()
            ->take($request['limit']);
    }

    /**
     * Получаем типы ящиков сгруппированные по uik_id
     * @param int $recognition
     * @param int $camera
     * @return \Illuminate\Support\Collection
     */
    public function findPrevRecognition(int $recognition, int $camera)
    {
        $columns = [
            'box_recognitions.cam_numeric_id',
            'images.image_id',
            'images.raw_image',
            'images.width',
            'images.height',
            'images.datetime',
            'boxes_info.box_id',
            'boxes_info.type',
            'boxes_info.type_conf',
            'boxes_info.box_bbox_coords',
            'boxes_info.box_quality',
            'boxes_info.conf',
            'boxes_info.cap_type',
            'boxes_info.cap_ort_bbox',
            'boxes_info.cap_rot_bbox',
            'boxes_info.normalized_width_k',
            'boxes_info.normalized_dist_k',
            'boxes_info.centroid_k',
            'boxes_info.cap_centroid_k',
            'box_recognitions.recognition_id',
            'box_recognitions.cam_quality',
            'box_recognitions.boxes_num',
            'box_recognitions.boxes_flag',
            'box_recognitions.checked',
            'box_recognitions.check_datetime',
            'box_recognitions.recognition_datetime'
        ];

        $model = $this->startCondition()
            ->select($columns)
            ->where('box_recognitions.checked', true)
            ->where('box_recognitions.recognition_id', '<', $recognition)
            ->where('box_recognitions.cam_numeric_id', '=', $camera)
            ->whereNotNull('boxes_info.box_id')
            ->leftJoin('box_recognitions_images', 'box_recognitions.recognition_id', '=', 'box_recognitions_images.recognition_id')
            ->leftJoin('images', 'box_recognitions_images.image_id', '=', 'images.image_id')
            ->leftJoin('boxes_info', 'box_recognitions.recognition_id', '=', 'boxes_info.recognition_id')
            ->orderBy('box_recognitions.recognition_id', 'DESC')
            ->get()
            ->groupBy('recognition_id')->values();

        return !$model->count() ? null : $model[0]
            ->groupBy('cam_numeric_id')
            ->map(function ($recognition) {
                $activeCamera = $recognition->reduce(function ($carry, $item) {
                    if (!$carry->count() && $item->box_id && $item->normalized_width_k) return $item;

                    return $item->normalized_width_k && $item->box_id && $item->normalized_width_k > $carry->normalized_width_k ? $item : $carry;
                }, collect());

                $activeCamera = $activeCamera->count() ? $activeCamera->cam_numeric_id : null;

                return collect([
                    'cam_numeric_id' => $recognition[0]->cam_numeric_id,
                    'recognition_id' => $recognition[0]->recognition_id,
                    'number' => $recognition[0]->cam_num,
                    'countable' => $activeCamera == $recognition[0]->cam_numeric_id,
                    'boxTypes' => $recognition->pluck('type')->filter(function ($item) {
                        return !!$item;
                    })->unique(),
                    'image' => collect([
                        "image_id" => $recognition[0]['image_id'],
                        "raw_image" => asset($recognition[0]['raw_image']),
                        "width" => $recognition[0]['width'],
                        "height" => $recognition[0]['height'],
                        "datetime" => $recognition[0]['datetime'],
                        "boxes" => $recognition->map(function ($item) {
                            return collect([
                                'box_id' => $item['box_id'],
                                'type' => $item['type'],
                                'box_bbox_coords' => $item['box_bbox_coords'],
                                'box_quality' => $item['box_quality'],
                                'conf' => $item['conf'],
                                'type_conf' => $item['type_conf'],
                                'normalized_dist_k' => $item['normalized_dist_k'],
                                'centroid_k' => $item['centroid_k'],
                                'cap_centroid_k' => $item['cap_centroid_k'],
                                'cap_type' => $item['cap_type'],
                                'cap_ort_bbox' => $item['cap_ort_bbox'],
                                'cap_rot_bbox' => $item['cap_rot_bbox'],
                                'normalized_width_k' => $item['normalized_width_k'],
                            ]);
                        }),
                    ])
                ]);
            })->first();
    }

    /**
     * Получаем типы ящиков сгруппированные по uik_id
     * @return \Illuminate\Support\Collection
     */
    public function findBoxTypesGroupByUiks(): \Illuminate\Support\Collection
    {
        $columns = [
            'box_recognitions.uik_id',
            'boxes_info.type'
        ];

        $groups = [
            'box_recognitions.uik_id',
            'boxes_info.type'
        ];

        return $this->startCondition()
            ->leftJoin('boxes_info', 'boxes_info.recognition_id', '=', 'box_recognitions.recognition_id')
            ->select($columns)
            ->groupBy($groups)
            ->get()
            ->filter(function ($item) {
                return $item->type !== null;
            })
            ->groupBy('uik_id')
            ->map(function ($item) {
                return [
                    'uik_id' => $item[0]->uik_id,
                    'box_types' => $item
                        ->map(function ($item) {
                            return collect($item)
                                ->put('id', $item->type)
                                ->put('name', BoxTypesHelper($item->type))
                                ->forget('type')
                                ->forget('uik_id');
                        })
                ];
            });
    }
}
