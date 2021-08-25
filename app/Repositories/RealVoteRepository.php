<?php

namespace App\Repositories;

use App\Models\RealVote as Model;

use Illuminate\Support\Facades\DB;

use Jenssegers\Date\Date;

/**
 * Class VoteRepository
 * @package App\Repositories
 */
class RealVoteRepository extends BaseRepository
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
    public function findRealVotesForModal(int $uikId)
    {
        $columns = [
            'videos.check_id',
            'videos.start_local_datetime',
            'videos.end_local_datetime',
            'real_votes.real_vote_id as vote_id',
            'check_table.voting_date_start',
            'check_table.voting_date_end',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('videos', 'real_votes.video_id', '=', 'videos.video_id')
            ->leftJoin('check_table', 'videos.check_id', '=', 'check_table.check_id')
            ->where('real_votes.uik_id', $uikId)
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
                    "vote_date" => Date::parse($item->start_local_datetime)->format('j F'),
                    "vote_time" => Date::parse($item->start_local_datetime)->format('H:i') . " - " . Date::parse($item->end_local_datetime)->format('H:i'),
                ]);
            })
            ->groupBy('vote_date')
            ->map(function ($item, $key) {
                return collect([
                    'day' => $key,
                    'times' => $item->groupBy('vote_time')->map(function ($item, $key) {
                        return collect([
                            'time' => $key,
                            'votes' => $item->count()
                        ]);
                    })->values()
                ]);
            })->values();
    }
}
