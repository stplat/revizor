<?php

namespace App\Repositories;

use App\Models\Violation as Model;

use Jenssegers\Date\Date;

/**
 * Class ViolationRepository
 * @package App\Repositories
 */
class ViolationRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем список событий для типа "Не хватает видео"
     * @param int $cameraId
     * @param string $duration (moreMinute, moreHour, none)
     * @return ?\Illuminate\Support\Collection
     */
    public function findViolationListWithNotVideoForModal(int $cameraId, string $duration = null): ?\Illuminate\Support\Collection
    {
        $columns = [
            'violation_id as id',
            'violation_datetime_start as date_start',
            'violation_datetime_end as date_end',
        ];

        $model = $this->startCondition()
            ->select($columns)
            ->where('violation_type_id', 1)
            ->where('cam_numeric_id', $cameraId)
            ->orderBy('violation_datetime_start', 'DESC')
            ->get();

        if ($duration) {
            $model = $model->filter(function ($item) use ($duration) {
                $diff = strtotime($item->date_end) - strtotime($item->date_start);
                switch ($duration) {
                    case "moreHour":
                        return $diff > 3600;
                    case "moreMinute":
                        return $diff > 60;
                    case "none":
                        return !$item->date_end;
                }
            })->values();
        }

        Date::setLocale('ru');

        return $model->map(function ($item) {
            $duration = "";
            $appeared = "";

            if ($item->date_end) {
                $diff = date_diff(new \Datetime($item->date_end), new \Datetime($item->date_start));
                $appeared = $item->date_end;

                if ($diff->d) {
                    $duration = "$diff->d д ";
                }

                if ($diff->h) {
                    $duration .= "$diff->h ч ";
                }

                if ($diff->i) {
                    $duration .= "$diff->i мин ";
                }

                if ($diff->s) {
                    $duration .= "$diff->s сек";
                }
            }

            return collect([
                'date' => Date::parse($item->date_start)->format('d.m'),
                'disappeared' => $item->date_start,
                'appeared' => $appeared,
                'duration' => $duration,
                'start' => $item->date_start,
                'end' => $item->date_end
            ]);
        });
    }

    /**
     * Получаем данные по одному событию для подсказки (нарушению)
     * @param int $id
     * @return ?Model
     */
    public function findViolationForModal($id)
    {
        $columns = [
            'violations.violation_id as id',
            'violations.violation_datetime_start as date_start',
            'violations.violation_datetime_end as date_end',
            'violations.violation_type_id as type',
            'violations.status_id as status',
            'violations.blocked',
            'violation_types.type_name',
            'violation_statuses.status_name',
            'box_recognitions.boxes_num',
            'boxes_info.normalized_width_k',
            'violation_protocols.text_type',
            'violation_protocols.applicant_id',
            'violation_protocols.decree_id',
            'violation_protocols.protocol_link',
            'violation_protocols.response_link',
            'violation_auto_text.violation_text as auto_text',
            'violations_custom_text.violation_text as custom_text',
            'users.username',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('violation_images', 'violations.violation_id', '=', 'violation_images.violation_id')
            ->leftJoin('box_recognitions_images', 'violation_images.image_id', '=', 'box_recognitions_images.image_id')
            ->leftJoin('box_recognitions', 'box_recognitions_images.recognition_id', '=', 'box_recognitions.recognition_id')
            ->leftJoin('boxes_info', 'box_recognitions_images.recognition_id', '=', 'boxes_info.recognition_id')
            ->leftJoin('violation_types', 'violations.violation_type_id', '=', 'violation_types.violation_type_id')
            ->leftJoin('violation_statuses', 'violations.status_id', '=', 'violation_statuses.status_id')
            ->leftJoin('violation_auto_text', 'violations.violation_type_id', '=', 'violation_auto_text.violation_type_id')
            ->leftJoin('violation_protocols', 'violations.violation_id', '=', 'violation_protocols.violation_id')
            ->leftJoin('violations_custom_text', 'violation_protocols.text_id', '=', 'violations_custom_text.custom_text_id')
            ->leftJoin('violation_status_changes', 'violations.violation_id', '=', 'violation_status_changes.violation_id')
            ->leftJoin('users', 'violation_status_changes.changed_by', '=', 'users.user_id')

            ->where('violations.violation_id', $id)
            ->orderBy('violation_status_changes.status_datetime', 'DESC')
            ->get()
            ->reduce(function ($carry, $item) {
                $text = "";

                if ($item->status == 1 && $item->auto_text) {
                    $text = $item->auto_text;
                } else if ($item->text_type === 'custom' && $item->custom_text) {
                    $text = $item->custom_text;
                }
                return collect([
                    'id' => $item->id,
                    'blocked' => $item->blocked,
                    'date_start' => $item->date_start,
                    'date_end' => $item->date_end,
                    'type' => $item->type,
                    'type_name' => $item->type_name,
                    'applicant' => $item->applicant_id,
                    'protocol_link' => $item->protocol_link,
                    'response_link' => $item->response_link,
                    'decree' => $item->decree_id,
                    'text' => $text,
                    'status' => $item->status,
                    'status_name' => $item->status_name,
                    'boxes_num' => $item->boxes_num,
                    'normalized_width_k' => $item->normalized_width_k ?? 1,
                    'username' => $carry ?  $carry['username'] : $item->username,
                ]);
            }, null);
    }

    /**
     * Получаем список участков с типами нарушений
     * @param int $check_id
     * @param array $status_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findViolationCams(int $check_id, array $status_id = []): \Illuminate\Database\Eloquent\Collection
    {
        $columns = [
            'violations.violation_id as id',
            'violations.cam_numeric_id',
            'violations.violation_datetime_start',
            'uiks.region_number',
            'uiks.uik_number',
            'cameras.cam_num',
            'violation_types.violation_type_id as type_id',
            'violation_types.type_name',
            'violation_statuses.status_id',
            'violation_statuses.status_name',

        ];

        $model = $this->startCondition()
            ->select($columns)
            ->leftJoin('cameras', 'cameras.cam_numeric_id', '=', 'violations.cam_numeric_id')
            ->leftJoin('uiks', 'uiks.uik_id', '=', 'cameras.uik_id')
            ->leftJoin('violation_types', 'violation_types.violation_type_id', '=', 'violations.violation_type_id')
            ->leftJoin('violation_statuses', 'violation_statuses.status_id', '=', 'violations.status_id')
            ->where('cameras.check_id', $check_id);

        if (count($status_id)) {
            $model = $model->whereIn('violation_statuses.status_id', $status_id);
        }

        return $model
            ->with(['cameras:uik_id,cam_numeric_id,cam_id'])
            ->orderBy('violations.violation_datetime_start', 'DESC')
            ->get();
    }

    /**
     * Получаем список участков с типами нарушений
     * @param int $checkId
     * @param array $statuses
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findViolationForEventTable(int $checkId, array $statuses = [])
    {
        $columns = [
            'violations.violation_id as id',
            'violations.violation_datetime_start as date_start',
            'violations.violation_datetime_end as date_end',
            'violations.cam_numeric_id',
            'violations.blocked',
            'violation_types.violation_type_id',
            'violation_types.type_name',
            'violation_statuses.status_id',
            'violation_statuses.status_name',
            'uiks.uik_id',
            'uiks.region_number',
            'uiks.uik_number',
            'cameras.cam_num',
        ];

        $model =  $this->startCondition()
            ->select($columns)
            ->leftJoin('cameras', 'violations.cam_numeric_id', '=', 'cameras.cam_numeric_id')
            ->leftJoin('uiks', 'uiks.uik_id', '=', 'cameras.uik_id')
            ->leftJoin('violation_types', 'violations.violation_type_id', '=', 'violation_types.violation_type_id')
            ->leftJoin('violation_statuses', 'violations.status_id', '=', 'violation_statuses.status_id')
            ->orderBy('violations.creation_datetime', 'DESC')
            ->where('cameras.check_id', $checkId);

        if (count($statuses)) {
            $model = $model->whereIn('violation_statuses.status_id', $statuses);
        }

        return $model
            ->get()
            ->reduce(function ($carry, $item) {
                if ($item['violation_type_id'] == 1 && $carry->contains('cam_numeric_id', $item->cam_numeric_id)) {
                    return $carry->map(function ($carry) use ($item) {
                        if ($carry['violation_type_id'] == 1 && $carry['cam_numeric_id'] == $item['cam_numeric_id']) {
                            return collect([
                                "id" => collect($carry['id'])->push($item['id']),
                                "date_start" => collect($carry['date_start'])->push($item['date_start']),
                                "date_end" => collect($carry['date_end'])->push($item['date_end']),
                                "cam_numeric_id" => $carry['cam_numeric_id'],
                                "violation_type_id" => $carry['violation_type_id'],
                                "type_name" => $carry['type_name'],
                                "status_id" => $carry['status_id'],
                                "status_name" => $carry['status_name'],
                                "region_number" => $carry['region_number'],
                                "uik_id" => $carry['uik_id'],
                                "uik_number" => $carry['uik_number'],
                                "cam_num" => $carry['cam_num'],
                                "blocked" => $carry['blocked'],
                            ]);
                        } else {
                            return $carry;
                        }
                    });
                } else {
                    return $carry->push($item);
                }
            }, collect());
    }
}
