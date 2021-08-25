<?php

namespace App\Repositories;

use App\Models\Video as Model;

use Jenssegers\Date\Date;

/**
 * Class VideosProcessingRepository
 * @package App\Repositories
 */
class VideoRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем видео по дате и времени
     * @param int $camera
     * @param object $date
     * @return \App\Models\Video
     */
    public function findVideoForModal(int $camera, object $date): ?\App\Models\Video
    {
        $columns = [
            'videos.direct_link as file',
            'storage_servers.auth_login as p1',
            'storage_servers.auth_password as p2',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('storage_servers', 'videos.storage_server_id', '=', 'storage_servers.storage_server_id')
            ->whereDate('start_local_datetime', '=', date('Y/m/d h:i:s', strtotime($date->dateFrom)))
            ->whereDate('end_local_datetime', '=', date('Y/m/d h:i:s', strtotime($date->dateTo)))
            ->where('cam_numeric_id', $camera)
            ->first();
    }

    /**
     * Получаем дату и время видео для графика "Не хватает видео"
     * @param int $checkId
     * @param int $cameraId
     * @return \App\Models\Video
     */
    public function findVideoTimesForModalChart(int $checkId, int $cameraId)
    {
        $columns = [
            'check_table.voting_date_start',
            'check_table.voting_date_end',
            'videos.start_local_datetime',
            'videos.end_local_datetime',
        ];

        function roundToQuarterHour($timestring)
        {
            $minutes = date('i', strtotime($timestring));
            $minutes = $minutes - ($minutes % 15);

            return $minutes ? 1 / (60 / ($minutes - ($minutes % 15))) : 0;
        }

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('check_table', 'videos.check_id', '=', 'check_table.check_id')
            ->where('check_table.check_id', $checkId)
            ->where('videos.cam_numeric_id', $cameraId)
            ->orderBy('videos.start_local_datetime')
            ->get()
            ->filter(function ($item) {
                $videoDate = date("Y-m-d", strtotime($item->start_local_datetime));
                $checkStart = date("Y-m-d", strtotime($item->voting_date_start));
                $checkEnd = date("Y-m-d", strtotime($item->voting_date_end));

                $videoTimeStartHour = (int)date("H", strtotime($item->start_local_datetime));

                $videoTimeStart = date('H', strtotime($item->start_local_datetime)) + roundToQuarterHour($item->start_local_datetime);
                $videoTimeEnd =  date('H', strtotime($item->end_local_datetime)) + roundToQuarterHour($item->end_local_datetime);

                return $checkStart <= $videoDate && $videoDate <= $checkEnd && 8 <= $videoTimeStartHour && $videoTimeStartHour < 20 && $videoTimeStart < $videoTimeEnd;
            })
            ->map(function ($item) {
                return collect([
                    "vote_conf" => $item->vote_conf,
                    "vote_date" => Date::parse($item->start_local_datetime)->format('j F'),
                    "vote_time_start" => Date::parse($item->start_local_datetime)->format('H:i'),
                    "vote_time_end" => Date::parse($item->end_local_datetime)->format('H:i'),

                    "timeStart" => Date::parse($item->start_local_datetime)->format('H:i:s'),
                    "timeEnd" => Date::parse($item->end_local_datetime)->format('H:i:s'),
                    "hourStart" => Date::parse($item->start_local_datetime)->format('H') + roundToQuarterHour($item->start_local_datetime),
                    "hourEnd" => Date::parse($item->end_local_datetime)->format('H') + roundToQuarterHour($item->end_local_datetime),
                ]);
            })->values()
            ->groupBy('vote_date')
            ->map(function ($item) {
                return collect([
                    'day' => $item[0]['vote_date'],
                    'timings' => $item->reduce(function ($carry, $item) {
                        if (!$carry->count()) {
                            return $carry->push(collect([
                                'durationInHours' => ($item['hourStart'] - 8) == 0 ? $item['hourEnd'] : $item['hourStart'],
                                'broadcast' => ($item['hourStart'] - 8) == 0,
                                'timeStart' => ($item['hourStart'] - 8) == 0 ? $item['timeStart'] : "08:00:00",
                                'timeEnd' => ($item['hourStart'] - 8) == 0 ? $item['timeEnd'] : $item['timeStart']
                            ]));
                        } else {
                            $lastHour = $carry->last()['durationInHours'];
                            $diff = $item['hourStart'] - $lastHour;
                            $quarterHour = ($diff ? 1 / $diff : 0);

                            if ($quarterHour == 0 && $carry->last()['broadcast']) {
                                $carry->last()->put('durationInHours', $item['hourEnd'])->put('timeEnd', $item['timeEnd']);
                            } else {
                                $carry->push(collect([
                                    'durationInHours' => $item['hourStart'],
                                    'broadcast' => false,
                                    'timeStart' => $carry->last()['timeEnd'],
                                    'timeEnd' => $item['timeStart']
                                ]));

                                $carry->push(collect([
                                    'durationInHours' => $item['hourEnd'],
                                    'broadcast' => true,
                                    'timeStart' => $item['timeStart'],
                                    'timeEnd' => $item['timeEnd']
                                ]));
                            }

                            return $carry;
                        }
                    }, collect())
                ]);
            })->values();
    }
}
