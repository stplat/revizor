<?php

namespace App\Repositories;

use App\Models\VotesOfficial as Model;


use Illuminate\Support\Facades\DB;

/**
 * Class VoteRepository
 * @package App\Repositories
 */
class VoteOfficialRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     *  Получаем данные по явке для подсказки в модалке
     * @param int $uikId
     * @return \Illuminate\Support\Collection
     */
    public function findOfficialVotesForTip(int $uikId): ?\Illuminate\Support\Collection
    {
        $columns = [
            'votes_official.ballots_in_ballot_boxes_after_election',
            'uiks.uik_id',
            'uiks.voters_registered',
            'votes_sum.voters_turnout',
            'votes_deviation.deviation',
            'cameras.skip_votes_summary',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('uiks', 'votes_official.uik_id', '=', 'uiks.uik_id')
            ->leftJoin('votes_sum', 'votes_official.uik_id', '=', 'votes_sum.uik_id')
            ->leftJoin('votes_deviation', 'votes_official.uik_id', '=', 'votes_deviation.uik_id')
            ->leftJoin('cameras', 'votes_official.uik_id', '=', 'cameras.uik_id')
            ->where('votes_official.uik_id', $uikId)
            ->get()
            ->groupBy('uik_id')
            ->map(function ($item) {
                return collect([
                    'ballots_in_ballot_boxes_after_election' => $item->reduce(function ($carry, $item) {
                        return $carry + $item->ballots_in_ballot_boxes_after_election;
                    }, 0),
                    'voters_registered' => $item->reduce(function ($carry, $item) {
                        return $carry + $item->voters_registered;
                    }, 0),
                    'voters_turnout' => $item->reduce(function ($carry, $item) {
                        return $carry + $item->voters_turnout;
                    }, 0),
                    'deviation' => $item->reduce(function ($carry, $item) {
                        return $carry + $item->deviation;
                    }, 0),
                    'skip_votes_summary' => $item->reduce(function ($carry, $item) {
                        return $carry ?: !!$item->skip_votes_summary;
                    }, false),
                ]);
            })->first();
    }
}
