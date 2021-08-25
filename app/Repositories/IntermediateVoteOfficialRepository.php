<?php

namespace App\Repositories;

use App\Models\IntermediateVotesOfficial as Model;

use App\Models\IntermediateVotesOfficial;
use App\Models\VotesOfficial;
use App\Models\Camera;

use Illuminate\Support\Facades\DB;

use Jenssegers\Date\Date;

/**
 * Class IntermediateVoteOfficialRepository
 * @package App\Repositories
 */
class IntermediateVoteOfficialRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем промежуточную явку на участке ревизора
     * @param int $uikId
     * @param string $violationDateStart
     * @return \App\Models\IntermediateVotesOfficial
     */
    public function findIntermediateOfficialVotesForTip(int $uikId, string $violationDateStart)
    {
        $columns = [
            'intermediate_votes_official.id',
            'intermediate_votes_official.date as intermediate_date',
            'intermediate_votes_official.voters_voted_in_ballot_box',
            'uiks.voters_registered',
            'votes_sum.id as vote_sum_id',
            'votes_sum.voters_turnout',
            'votes_sum.date as vote_sum_date',
            'votes_deviation.id as deviation_id',
            'votes_deviation.date_start as deviation_datestart',
            'votes_deviation.deviation',
            'cameras.skip_votes_summary',
        ];

        $model = $this->startCondition()
            ->select($columns)
            ->leftJoin('cameras', 'intermediate_votes_official.uik_id', '=', 'cameras.uik_id')
            ->leftJoin('votes_sum', 'intermediate_votes_official.uik_id', '=', 'votes_sum.uik_id')
            ->leftJoin('votes_deviation', 'intermediate_votes_official.uik_id', '=', 'votes_deviation.uik_id')
            ->leftJoin('uiks', 'intermediate_votes_official.uik_id', '=', 'uiks.uik_id')
            ->where('intermediate_votes_official.uik_id', $uikId)
            ->get()
            ->groupBy('id');

        if ($model->count() > 1) {
            $model = $model->filter(function ($item) use ($violationDateStart) {
                dd($item->toArray());

                return strtotime($violationDateStart) == strtotime($item[0]->intermediate_date);
            });
        }

        return $model->map(function ($item) use ($violationDateStart) {
            return collect([
                'voters_voted_in_ballot_box' => $item[0]->voters_voted_in_ballot_box,
                'violation_datetime_start' => Date::parse($violationDateStart)->format('j F'),
                'voters_registered' => $item[0]->voters_registered,
                'voters_turnout' => $item->groupBy('vote_sum_id')->reduce(function ($carry, $item) {
                    return $carry + $item[0]->voters_turnout;
                }, 0),
                'deviation' => $item->groupBy('deviation_id')->reduce(function ($carry, $item) {
                    return $carry + $item[0]->deviation;
                }, 0),
                'skip_votes_summary' => $item->reduce(function ($carry, $item) {
                    return $carry ?: !!$item->skip_votes_summary;
                }, false),
            ]);
        })->first();
    }
}
