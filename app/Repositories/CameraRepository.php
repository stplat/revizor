<?php

namespace App\Repositories;

use App\Models\Camera as Model;
use App\Models\VideosProcessing;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;


/**
 * Class CameraRepository
 * @package App\Repositories
 */
class CameraRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем список камер на участке
     * @param int $uikId
     * @return \Illuminate\Support\Collection
     */
    public function findCameraListForModal(int $uikId): ?Object
    {
        $columns = [
            'cameras.cam_numeric_id as id',
            'cameras.cam_num as number',
            'violations.violation_id as violation',
            'videos.start_local_datetime',
            'videos.end_local_datetime',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('violations', 'cameras.cam_numeric_id', '=', 'violations.cam_numeric_id')
            ->leftJoin('videos', 'cameras.cam_numeric_id', '=', 'videos.cam_numeric_id')
            ->where('cameras.uik_id', $uikId)
            ->whereNotNull('videos.start_local_datetime')
            ->orderBy('cam_num')
            ->get()
            ->map(function ($item) {
                return (object)[
                    'id' => $item->id,
                    'number' => $item->number,
                    'violation' => $item->violation,
                    'date' => $item->start_local_datetime ? date('d.m.Y', strtotime($item->start_local_datetime)) : null,
                    'timeStart' => $item->start_local_datetime ? date('H:i:s', strtotime($item->start_local_datetime)) : null,
                    'timeEnd' => $item->end_local_datetime ? date('H:i:s', strtotime($item->end_local_datetime)) : null,
                ];
            })
            ->groupBy('id')
            ->map(function ($item) {
                return (object)[
                    'id' => $item[0]->id,
                    'number' => $item[0]->number,
                    'violation' => $item[0]->violation,
                    'dates' => $item->groupBy('date')
                        ->map(function ($item) {
                            return (object)[
                                'date' => $item[0]->date,
                                'times' => $item->map(function ($item) {
                                    return $item->timeStart . ' - ' . $item->timeEnd;
                                }),
                            ];
                        })->values()
                ];
            })->values();
    }

    /**
     * Получаем камеры с фотографиями для модалки
     * @params int $violationInt
     * @return \Illuminate\Support\Collection
     */
    public function findImagesGroupByCameraForModal(int $violationId): \Illuminate\Support\Collection
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
            'violation_images.violation_id',
            'boxes_info.type',
            'boxes_info.box_bbox_coords',
            'boxes_info.cap_type',
            'boxes_info.cap_ort_bbox',
            'boxes_info.cap_rot_bbox',
            'boxes_info.normalized_width_k',
            'box_recognitions.recognition_id',
            'box_recognitions.checked',
            'box_recognitions.check_datetime',
            'users.username',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('images', 'cameras.cam_numeric_id', '=', 'images.cam_numeric_id')
            ->leftJoin('box_recognitions_images', 'images.image_id', '=', 'box_recognitions_images.image_id')
            ->leftJoin('boxes_info', 'box_recognitions_images.recognition_id', '=', 'boxes_info.recognition_id')
            ->leftJoin('box_recognitions', 'boxes_info.recognition_id', '=', 'box_recognitions.recognition_id')
            ->leftJoin('violation_images', 'images.image_id', '=', 'violation_images.image_id')
            ->leftJoin('users', 'box_recognitions.checked_by', '=', 'users.user_id')
            // ->whereNotNull('boxes_info.type')
            ->where('violation_images.violation_id', $violationId)
            ->get()
            ->groupBy('cam_numeric_id')
            ->map(function ($item) {
                return (object)[
                    'id' => $item[0]->cam_numeric_id,
                    'number' => $item[0]->cam_num,
                    'code' => $item[0]->cam_id,
                    'countable' => $item[0]->main,
                    'images' => $item->groupBy('image_id')->map(function ($item) {
                        return (object)[
                            'id' => $item[0]->image_id,
                            'violation' => $item[0]->violation_id,
                            'path' => $item[0]->raw_image,
                            'width' => $item[0]->width,
                            'height' => $item[0]->height,
                            'date' => $item[0]->datetime,
                            'recognition_id' => $item[0]->recognition_id,
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
                            'username' => $item[0]->username,
                            'boxes' => $item->map(function ($item) {
                                return (object)[
                                    'type' => $item->type,
                                    'box_bbox_coords' => $item->box_bbox_coords,
                                    'cap_type' => $item->cap_type,
                                    'cap_ort_bbox' => $item->cap_ort_bbox,
                                    'cap_rot_bbox' => $item->cap_rot_bbox,
                                ];
                            })
                        ];
                    })->values(),
                ];
            })->values();
    }

    /**
     * Находим список video_id по проверке
     * @param array $uik_ids
     * @return
     */
    public function findVideosByUikId(array $uik_ids): \Illuminate\Support\Collection
    {
        $columns = [
            'videos.cam_numeric_id',
            'videos.video_id',
            'videos.start_local_datetime',
            'videos.end_local_datetime',
            'videos.vote_datetime',
            'videos.votes',
        ];

        return $this->startCondition()
            ->select($columns)
            ->where('main', true)
            ->whereIn('uik_id', $uik_ids)
            ->joinSub(
                $this->subQueryStartAndEndLocalDatetime(),
                'videos',
                'videos.cam_numeric_id',
                '=',
                'cameras.cam_numeric_id'
            )
            ->get()
            ->filter(function ($item) {
                $start = strtotime($item->start_local_datetime);
                $end = strtotime($item->end_local_datetime);

                $between = strtotime($item->vote_datetime);

                return $start < $between && $between < $end && $item->votes > 5;
            })
            ->groupBy('video_id')
            ->map(function ($item) {
                return collect((object)[
                    'video_id' => $item[0]->video_id,
                    'cam_numeric_ids' => array_unique(array_keys($item->groupBy('cam_numeric_id')->toArray()))
                ]);
            })
            ->values();
    }

    /**
     * Подзапрос для videos_processing
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryStartAndEndLocalDatetime(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'videos.cam_numeric_id',
            'videos.video_id',
            'videos.start_local_datetime',
            'videos.end_local_datetime',
            'votes.vote_datetime',
            'votes.sum as votes',
        ];

        return VideosProcessing::select($columns)
            ->leftJoin('videos', 'videos.video_id', '=', 'videos_processing.video_id')
            ->leftJoinSub(
                $this->subQueryVotesGroupByVideoId(),
                'votes',
                'votes.video_id',
                '=',
                'videos_processing.video_id'
            )
            ->where('videos_processing.task_type', 'counting');
    }

    /**
     * Подзапрос голосов по video_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVotesGroupByVideoId(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'votes.video_id',
            'votes.vote_datetime',
            DB::raw('SUM(votes.vote_conf) as sum'),
        ];

        $groups = [
            'votes.video_id',
            'votes.vote_datetime',

        ];

        return Vote::select($columns)
            ->groupBy($groups);
    }

    /**
     * Получаем список участков с типами нарушений
     * @param int $check_id
     * @return \Illuminate\Support\Collection
     */
    public function findUikViolationTypesByCheckId($check_id): \Illuminate\Support\Collection
    {
        $columns = [
            'cameras.uik_id',
            'violation_types.type_name',
            'violation_types.violation_type_id'
        ];

        return $this->model
            ->select($columns)
            ->leftJoin('violations', 'violations.cam_numeric_id', '=', 'cameras.cam_numeric_id')
            ->leftJoin('violation_types', 'violation_types.violation_type_id', '=', 'violations.violation_type_id')
            ->where('check_id', $check_id)
            ->get()
            ->groupBy('uik_id')
            ->map(function ($item) {
                return collect([
                    'uik_id' => $item[0]->uik_id,
                    'violation_types' => $item
                        ->map(function ($item) {
                            return collect($item)
                                ->put('id', $item->violation_type_id)
                                ->put('name', $item->type_name)
                                ->forget('type_name')
                                ->forget('violation_type_id')
                                ->forget('uik_id');
                        })
                ]);
            });
    }

    /**
     * Получаем список участков со статусами нарушений
     * @param int $check_id
     * @return \Illuminate\Support\Collection
     */
    public function findUikViolationStatusesByCheckId($check_id): \Illuminate\Support\Collection
    {
        $columns = [
            'cameras.uik_id',
            'violation_statuses.status_name',
            'violation_statuses.status_id'
        ];

        return $this->model
            ->select($columns)
            ->leftJoin('violations', 'violations.cam_numeric_id', '=', 'cameras.cam_numeric_id')
            ->leftJoin('violation_statuses', 'violation_statuses.status_id', '=', 'violations.status_id')
            ->where('check_id', $check_id)
            ->get()
            ->groupBy('uik_id')
            ->map(function ($item) {
                return collect([
                    'uik_id' => $item[0]->uik_id,
                    'violation_statuses' => $item
                        ->map(function ($item) {
                            return collect($item)
                                ->put('id', $item->status_id)
                                ->put('name', $item->status_name)
                                ->forget('status_name')
                                ->forget('status_id')
                                ->forget('uik_id');
                        })
                ]);
            });
    }

    /**
     * Получаем фотографии сгруппированные по Участкам (uik_id)
     * @return \Illuminate\Support\Collection
     */
    public function findCameraImages(): \Illuminate\Support\Collection
    {
        $columns = [
            'cameras.uik_id',
            'cameras.cam_numeric_id',
            'images.raw_image',
            'images.image_id',
            'cameras.main',
        ];

        return $this->model
            ->join('images', 'images.cam_numeric_id', '=', 'cameras.cam_numeric_id')
            ->select($columns)
            ->orderBy('images.image_id', 'DESC')
            ->get()
            ->groupBy('uik_id')
            ->map(function ($item) {
                return $item->groupBy('cam_numeric_id')
                    ->map(function ($item) {
                        return $item[0];
                    })->values();
            });
    }
}
