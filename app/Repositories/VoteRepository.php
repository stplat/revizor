<?php

namespace App\Repositories;

use App\Models\Vote as Model;

use App\Models\IntermediateVotesOfficial;
use App\Models\VotesOfficial;
use App\Models\Camera;

use Illuminate\Support\Facades\DB;

use Jenssegers\Date\Date;

/**
 * Class VoteRepository
 * @package App\Repositories
 */
class VoteRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем явку на участке ревизора
     * @param int $uikId
     */
    public function findRevisorVotesForModal(int $uikId)
    {
        $columns = [
            'videos.check_id',
            'videos.start_local_datetime',
            'videos.end_local_datetime',
            'votes.vote_id',
            'votes.vote_conf',
            'check_table.voting_date_start',
            'check_table.voting_date_end',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('videos', 'votes.video_id', '=', 'videos.video_id')
            ->leftJoin('check_table', 'videos.check_id', '=', 'check_table.check_id')
            ->where('votes.uik_id', $uikId)
            ->orderBy('videos.start_local_datetime')
            ->get()
            ->filter(function ($item) {
                $videoDate = date("Y-m-d", strtotime($item->start_local_datetime));
                $checkStart = date("Y-m-d", strtotime($item->voting_date_start));
                $checkEnd = date("Y-m-d", strtotime($item->voting_date_end));

                return $checkStart <= $videoDate && $videoDate <= $checkEnd;
            })
            ->map(function ($item) {
                return collect([
                    "vote_id" => $item->vote_id,
                    "vote_conf" => $item->vote_conf,
                    "vote_date" => Date::parse($item->start_local_datetime)->format('j F'),
                    "vote_time" => Date::parse($item->start_local_datetime)->format('H:i') . " - " . Date::parse($item->end_local_datetime)->format('H:i'),
                ]);
            })
            ->groupBy('vote_date')
            ->map(function ($item, $key) {
                return collect([
                    'day' => $key,
                    'vote_conf' => collect([
                        'conf10' => $item->filter(function ($item) {
                            return 0 <= $item['vote_conf'] && $item['vote_conf'] < 10;
                        })->count(),
                        'conf20' => $item->filter(function ($item) {
                            return 10 <= $item['vote_conf'] && $item['vote_conf'] < 20;
                        })->count(),
                        'conf30' => $item->filter(function ($item) {
                            return 20 <= $item['vote_conf'] && $item['vote_conf'] < 30;
                        })->count(),
                        'conf40' => $item->filter(function ($item) {
                            return 30 <= $item['vote_conf'] && $item['vote_conf'] < 40;
                        })->count(),
                        'conf50' => $item->filter(function ($item) {
                            return 40 <= $item['vote_conf'] && $item['vote_conf'] < 50;
                        })->count(),
                        'conf60' => $item->filter(function ($item) {
                            return 50 <= $item['vote_conf'] && $item['vote_conf'] < 60;
                        })->count(),
                        'conf70' => $item->filter(function ($item) {
                            return 60 <= $item['vote_conf'] && $item['vote_conf'] < 70;
                        })->count(),
                        'conf80' => $item->filter(function ($item) {
                            return 70 <= $item['vote_conf'] && $item['vote_conf'] < 80;
                        })->count(),
                        'conf90' => $item->filter(function ($item) {
                            return 80 <= $item['vote_conf'] && $item['vote_conf'] < 90;
                        })->count(),
                        'conf100' => $item->filter(function ($item) {
                            return 90 <= $item['vote_conf'] && $item['vote_conf'] < 100;
                        })->count(),
                    ]),
                    'times' => $item->groupBy('vote_time')->map(function ($item, $key) {
                        return collect([
                            'time' => $key,
                            'votes' => $item->count()
                        ]);
                    })->values()
                ]);
            })->values();
    }

    /**
     *  Получаем данные по явке для подсказки в модалке
     * @param int $uikId
     * @return \Illuminate\Support\Collection
     */
    public function findVoteForTip(int $uikId)
    {
        $columns = [
            '*',
            'ballots_in_ballot_boxes_after_election'
        ];

        return $this->startCondition()
            ->select($columns)
            ->get();
    }

    /**
     *  Получаем голоса
     * @param int $check_id
     * @return \Illuminate\Support\Collection
     */
    public function findVoteGroupByUiksAndDateByCheckId(int $check_id): \Illuminate\Support\Collection
    {
        $columns = [
            'uiks.uik_id',
            'votes.vote_datetime',
            DB::raw('COUNT(votes.uik_id) as voters_turnout'),
        ];

        $groups = [
            'uiks.uik_id',
            'votes.vote_datetime',
        ];

        return $this->startCondition()
            ->select($columns)
            ->join('uiks', 'uiks.uik_id', '=', 'votes.uik_id')
            ->where('uiks.check_id', $check_id)
            ->groupBy($groups)
            ->get();
    }

    /**
     *  Получаем голоса обычной и официальной явки и группируем по Участкам
     * @param int $check_id
     * @return \Illuminate\Support\Collection
     */
    public function findVoteGroupByUiksByCheckId(int $check_id): \Illuminate\Support\Collection
    {
        $columns = [
            'uiks.uik_id',
            DB::raw('SUM(votes.vote_conf) as revisor_votes'),
            'votes_official.official_votes',
            'cameras.cam_numeric_id'
        ];

        $groups = [
            'uiks.uik_id',
            'votes_official.official_votes',
            'cameras.cam_numeric_id'
        ];

        return $this->startCondition()
            ->select($columns)
            ->join('uiks', 'uiks.uik_id', '=', 'votes.uik_id')
            ->leftJoinSub(
                $this->subQueryVotesOfficialGroupByUiks(),
                'votes_official',
                'votes_official.uik_id',
                '=',
                'votes.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryCameras(),
                'cameras',
                'cameras.uik_id',
                '=',
                'votes.uik_id'
            )
            ->where('uiks.check_id', $check_id)
            ->groupBy($groups)
            ->get()
            ->groupBy('uik_id')
            ->map(function ($item) {
                return (object)[
                    'uik_id' => $item[0]->uik_id,
                    'revisor_votes' => $item[0]->revisor_votes,
                    'official_votes' => $item[0]->official_votes,
                    'cameras' => $item->map(function ($item) {
                        return (object)[
                            'cam_numeric_id' => $item->cam_numeric_id
                        ];
                    })
                ];
            })
            ->values();
    }

    /**
     * Подзапрос, получаем официальную явку группируем по участкам
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVotesOfficialGroupByUiks(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
            DB::raw('SUM(ballots_in_ballot_boxes_after_election) as official_votes'),
        ];

        $groups = [
            'uik_id'
        ];

        return VotesOfficial::select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос, получаем официальную явку группируем по участкам
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryCameras(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
            'cam_numeric_id'
        ];

        $groups = [
            'uik_id',
            'cam_numeric_id'
        ];

        return Camera::select($columns)
            ->where('main', true)
            ->groupBy($groups);
    }

    /**
     *  Получаем голоса обычной и промежуточной явки и группируем по Участкам
     * @param int $check_id
     * @return \Illuminate\Support\Collection
     */
    public function findVoteIntermediateGroupByUiksByCheckId(int $check_id): \Illuminate\Support\Collection
    {
        $columns = [
            'uiks.uik_id',
            DB::raw('SUM(votes.vote_conf) as revisor_votes'),
            'intermediate_votes.date',
            'intermediate_votes.intermediate_votes',
            'cameras.cam_numeric_id'
        ];

        $groups = [
            'uiks.uik_id',
            'intermediate_votes.date',
            'intermediate_votes.intermediate_votes',
            'cameras.cam_numeric_id'
        ];

        return $this->startCondition()
            ->select($columns)
            ->join('uiks', 'uiks.uik_id', '=', 'votes.uik_id')
            ->joinSub(
                $this->subQueryVotesIntermediateGroupByUiks(),
                'intermediate_votes',
                'intermediate_votes.uik_id',
                '=',
                'votes.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryCameras(),
                'cameras',
                'cameras.uik_id',
                '=',
                'votes.uik_id'
            )
            ->where('uiks.check_id', $check_id)
            ->groupBy($groups)
            ->get()
            ->map(function ($item) {
                return collect($item)->put('key', $item->uik_id . $item->date);
            })
            ->groupBy('key')
            ->map(function ($item) {
                return (object)[
                    'uik_id' => $item[0]['uik_id'],
                    'date' => $item[0]['date'],
                    'revisor_votes' => $item[0]['revisor_votes'],
                    'intermediate_votes' => $item[0]['intermediate_votes'],
                    'cameras' => $item->map(function ($item) {
                        return (object)[
                            'cam_numeric_id' => $item['cam_numeric_id']
                        ];
                    })
                ];
            })
            ->values();
    }

    /**
     * Подзапрос, получаем официальную явку группируем по участкам
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVotesIntermediateGroupByUiks(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
            'date',
            DB::raw('SUM(voters_voted_in_ballot_box) as intermediate_votes'),
        ];

        $groups = [
            'uik_id',
            'date'
        ];

        return IntermediateVotesOfficial::select($columns)
            ->groupBy($groups);
    }
}
