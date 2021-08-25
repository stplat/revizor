<?php

namespace App\Repositories;

use App\Models\BoxRecognition;
use App\Models\Camera;
use App\Models\CameraBlacklist;
use App\Models\RealVote;
use App\Models\UikLabel;
use App\Models\VideosProcessing;
use App\Models\Violation;
use App\Models\VotesDeviation;
use Illuminate\Support\Facades\DB;

use App\Models\Uik as Model;

use App\Models\VotesOfficial;
use App\Models\Vote;

/**
 * Class UikRepository
 * @package App\Repositories
 */
class UikRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем участок (УИК) по id
     * @param int $id
     */
    public function findUikForTip(int $id): \Illuminate\Support\Collection
    {
        $columns = [
            'uiks.uik_id as id',
            'uiks.uik_number',
            'uiks.region_number',
            'uiks.address',
            'uiks.timezone_offset',
            'uiks.voters_registered',
            'cameras.main',
            'cameras.cam_num',
            'cameras.cam_id',
            'cameras.cam_numeric_id',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('cameras', 'uiks.uik_id', '=', 'cameras.uik_id')
            ->where('uiks.uik_id', $id)
            ->orderBy('cameras.cam_num')
            ->get()
            ->groupBy('id')
            ->reduce(function ($carry, $item) {
                return collect((object)[
                    'id' => $item[0]->id,
                    'name' => 'Регион ' . $item[0]->region_number . ', УИК ' . $item[0]->uik_number,
                    'address' => $item[0]->address,
                    'voters_registered' => $item[0]->voters_registered,
                    'countable' => !!$item->where('main', true)->count(),
                    'date' => now()->parse(strtotime(now()) + $item[0]->timezone_offset * 60),
                    'cameras' => $item->map(function ($item) {
                        return collect((object)[
                            'id' => $item->cam_numeric_id,
                            'number' => $item->cam_num,
                            'code' => $item->cam_id,
                            'countable' => $item->main
                        ]);
                    }),
                ]);
            }, collect());
    }



    /**
     * Получаем список учатков (УИКов)
     * @param int $check_id
     */
    public function findUiks($check_id = null): \Illuminate\Support\Collection
    {
        $columns = [
            'uik_id as id',
            'uik_number as number',
            'region',
            'region_number',
        ];

        $model = $this->startCondition()
            ->select($columns);

        if ($check_id) {
            $model->where('check_id', $check_id);
        }

        return $model
            ->get();
    }


    /**
     * Получаем список участков
     * @param int $check_id
     * @param array $uiks
     * @param array $regions
     * @param array $votes
     * @param array $votesOfficial
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findUiksByCheckId(
        int $check_id,
        array $uiks = [],
        array $regions = [],
        array $votes = [],
        array $votesOfficial = []
    ): \Illuminate\Database\Eloquent\Collection {
        $columns = [
            'uiks.uik_id',
            'uiks.region',
            'uiks.region_number',
            'uiks.uik_number',
            'uiks.address',
            'uiks.voters_registered',
            'uiks.latitude',
            'uiks.longitude',
            'votes.count as votes_count',
            'votes_official.count as votes_official_count',
            'votes_official_ballots.ballots_in_ballot_boxes_after_election as ballots_in_ballot_boxes_after_election',
            'votes_deviation.deviation',
            DB::raw('votes_deviation.deviation * 100 / ballots_in_ballot_boxes_after_election as deviation_percent'),
            'videos_processing.count as videos_processing_count',
            'cameras.count as cameras_count',
            'uik_labels_5.count as uik_labels_5_count',
            'uik_labels_6.count as uik_labels_6_count',
            'violations.count as violations_count',
            'votes_cameras_main_true.count as votes_cameras_main_true_count',
            'votes_cameras_main_false.count as votes_cameras_main_false_count',
            'votes_cameras_main_null.count as votes_cameras_main_null_count',
            'blacklist.count as blacklist_count',
            'blacklist.reason as blacklist_reason',
            'real_votes.count as real_votes_count',
            'uiks.tik as tik',
        ];

        $groups = [
            'uiks.uik_id',
            'votes.count',
            'votes_official.count',
            'votes_official_ballots.ballots_in_ballot_boxes_after_election',
            'votes_deviation.deviation',
            'videos_processing.count',
            'cameras.count',
            'uik_labels_5.count',
            'uik_labels_6.count',
            'violations.count',
            'votes_cameras_main_true.count',
            'votes_cameras_main_false.count',
            'votes_cameras_main_null.count',
            'blacklist.count',
            'real_votes.count',
            'uiks.tik',
            'blacklist.reason'
        ];

        $model = $this->startCondition()::select($columns)
            ->leftJoinSub(
                $this->subQueryVotesCount(),
                'votes',
                'votes.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryVotesOfficialCount(),
                'votes_official',
                'votes_official.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryVotesOfficialBallotsInBoxes(),
                'votes_official_ballots',
                'votes_official_ballots.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryVotesDeviation(),
                'votes_deviation',
                'votes_deviation.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosProcessingCount(),
                'videos_processing',
                'videos_processing.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryCameraCount(),
                'cameras',
                'cameras.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryUikLabelCount(),
                'uik_labels_5',
                'uik_labels_5.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryUikLabelCount(6),
                'uik_labels_6',
                'uik_labels_6.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryViolationCount(),
                'violations',
                'violations.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryVotesCameraMainCount(true),
                'votes_cameras_main_true',
                'votes_cameras_main_true.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryVotesCameraMainCount(false),
                'votes_cameras_main_false',
                'votes_cameras_main_false.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryVotesCameraMainCount(),
                'votes_cameras_main_null',
                'votes_cameras_main_null.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryCameraBlacklistCount(),
                'blacklist',
                'blacklist.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryRealVotesCount(),
                'real_votes',
                'real_votes.uik_id',
                '=',
                'uiks.uik_id'
            );

        if (count($uiks)) {
            $model->whereIn('uiks.uik_id', $uiks);
        }

        if (count($regions)) {
            $model->whereIn('uiks.region_number', $regions);
        }

        if (count($votes)) {
            $model->whereBetween('votes.count', $votes);
        }

        if (count($votesOfficial)) {
            $model->whereBetween('votes_official_ballots.ballots_in_ballot_boxes_after_election', $votesOfficial);
        }

        return $model
            ->where('check_id', $check_id)
            ->groupBy($groups)
            ->with(['cameras:uik_id,cam_numeric_id,cam_id'])
            ->get();
    }

    /**
     * Подзапрос на количество записей в votes (официальная явка)
     * сгруппированные по uik_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVotesCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
            DB::raw('COUNT(uik_id) as count')
        ];

        $groups = [
            'uik_id'
        ];

        return Vote::select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в votes основной камеры
     * сгруппированные по uik_id
     * @param bool|null $main
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVotesCameraMainCount(bool $main = null): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'votes.uik_id',
            DB::raw('COUNT(votes.uik_id) as count')
        ];

        $groups = [
            'votes.uik_id'
        ];

        $model = Vote::select($columns)
            ->leftJoin('cameras', 'cameras.uik_id', '=', 'votes.uik_id');

        if ($main !== null) {
            $model = $model->where('cameras.main', $main);
        } else {
            $model = $model->whereNull('cameras.main');
        }

        return $model->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в votes_official (официальная явка)
     * сгруппированные по uik_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVotesOfficialCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
            DB::raw('COUNT(uik_id) as count')
        ];

        $groups = [
            'uik_id'
        ];

        return VotesOfficial::select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество голосов в votes_official (официальная явка)
     * сгруппированные по uik_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVotesOfficialBallotsInBoxes(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
            DB::raw('SUM(ballots_in_ballot_boxes_after_election) as ballots_in_ballot_boxes_after_election')
        ];

        $groups = [
            'uik_id'
        ];

        return VotesOfficial::select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в votes_official (официальная явка)
     * сгруппированные по uik_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVotesDeviation(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
            DB::raw('AVG(deviation) as deviation')
        ];

        $groups = [
            'uik_id'
        ];

        return VotesDeviation::select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в videos_processing (официальная явка)
     * сгруппированные по uik_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVideosProcessingCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
            DB::raw('COUNT(uik_id) as count')
        ];

        $groups = [
            'uik_id'
        ];

        return VideosProcessing::select($columns)
            ->where('processed', false)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в cameras (официальная явка)
     * сгруппированные по uik_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryCameraCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
            DB::raw('COUNT(uik_id) as count')
        ];

        $groups = [
            'uik_id'
        ];

        return Camera::select($columns)
            ->where('skip_votes_summary', true)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в uik_labels (официальная явка)
     * сгруппированные по uik_id
     * @param int $label_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryUikLabelCount(int $label_id = 5): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
            DB::raw('COUNT(uik_id) as count')
        ];

        $groups = [
            'uik_id'
        ];

        return UikLabel::select($columns)
            ->where('label_id', $label_id)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в violations (официальная явка)
     * сгруппированные по uik_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryViolationCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'cameras.uik_id',
            DB::raw('COUNT(violations.cam_numeric_id) as count')
        ];

        $groups = [
            'cameras.uik_id'
        ];

        return Violation::select($columns)
            ->leftJoin('cameras', 'cameras.cam_numeric_id', '=', 'violations.cam_numeric_id')
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в cameras_blacklist
     * сгруппированные по uik_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryCameraBlacklistCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'cameras.uik_id',
            DB::raw('COUNT(cameras_blacklist.cam_numeric_id) as count'),
            DB::raw('MIN(cameras_blacklist.reason) as reason'),
        ];

        $groups = [
            'cameras.uik_id'
        ];

        return Camera::select($columns)
            ->leftJoin('cameras_blacklist', 'cameras_blacklist.cam_numeric_id', '=', 'cameras.cam_numeric_id')
            ->groupBy($groups);
    }

    /**
     * Подзапрос для типов ящиков
     * сгруппированные по uik_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryBoxTypes(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'box_recognitions.uik_id',
            DB::raw('COUNT(cameras_blacklist.cam_numeric_id) as count')
        ];

        $groups = [
            'cameras.uik_id'
        ];

        return BoxRecognition::select($columns)
            ->leftJoin('cameras_blacklist', 'cameras_blacklist.cam_numeric_id', '=', 'cameras.cam_numeric_id')
            ->groupBy($groups);
    }

    /**
     * Подзапрос для таблицы real_votes
     * сгруппированные по uik_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryRealVotesCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
            DB::raw('COUNT(real_vote_id) as count')
        ];

        $groups = [
            'uik_id'
        ];

        return RealVote::select($columns)
            ->groupBy($groups);
    }
}
