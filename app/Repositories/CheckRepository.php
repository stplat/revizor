<?php

namespace App\Repositories;

use App\Models\Check as Model;
use Illuminate\Support\Facades\DB;

use App\Models\BoxRecognition;
use App\Models\CheckingVideo;
use App\Models\RealCountingVideo;
use App\Models\Uik;
use App\Models\VideosToCheck;
use App\Models\VideosToCount;
use App\Models\VideosToFindBox;
use App\Models\VideosToRealCount;
use App\Models\VideosToSelectCam;
use App\Models\VotesDeviation;
use App\Models\VotesOfficial;
use App\Models\Vote;
use App\Models\IntermediateVotesOfficial;
use App\Models\Camera;
use App\Models\VideosProcessing;

/**
 * Class CheckRepository
 * @package App\Repositories
 */
class CheckRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем  просто список проверок (без связей)
     * @param int $id
     * @return
     */
    public function findCheckVotingDateEnd(int $id): ?String
    {
        $columns = [
            'voting_date_end as dateEnd',
        ];

        $model = $this->startCondition()
            ->select($columns)
            ->find($id);

        return $model ? $model->dateEnd : null;
    }

    /**
     * Получаем  просто список проверок (без связей)
     * @param null $order
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findCheckList($order = null): \Illuminate\Database\Eloquent\Collection
    {
        $columns = [
            'check_id as id',
            'check_name as name'
        ];

        return $this->startCondition()
            ->select($columns)
            ->orderBy('check_id', $order ? 'DESC' : 'ASC')
            ->get();
    }

    /**
     * Получаем список всех проверок
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findChecks(): \Illuminate\Database\Eloquent\Collection
    {
        $columns = [
            'check_table.check_id',
            'check_table.check_name',
            'check_types.type_name',
            'data_types.type_name as data_type_name',
            'uiks.count as uiks_count',
            'cameras.count as cameras_count'
        ];

        $groups = [
            'check_table.check_id',
            'check_table.check_name',
            'check_types.type_name',
            'data_types.type_name',
            'uiks.count',
            'cameras.count'
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('check_types', 'check_types.check_type_id', '=', 'check_table.check_type_id')
            ->leftJoin('data_types', 'data_types.data_type_id', '=', 'check_table.data_type_id')
            ->leftJoinSub(
                $this->subQueryUiksCountByCheckId(),
                'uiks',
                'uiks.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryCameraCountGroupByCheckId(),
                'cameras',
                'cameras.check_id',
                '=',
                'check_table.check_id'
            )
            ->groupBy($groups)
            ->orderBy('check_id')
            ->get();
    }

    /**
     * Подзапрос на количество записей в uiks сгруппированные по check_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryUiksCountByCheckId(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'check_id',
            DB::raw('COUNT(uik_id) as count')
        ];

        $groups = [
            'check_id'
        ];

        return Uik::select($columns)
            ->groupBy($groups);
    }

    /**
     * Ищем проверку и все связанные с ней данные
     * @param $id
     * @return Model|null
     */
    public function findCheck($id): ?Model
    {
        $columns = [
            'check_table.check_id',
            'check_table.check_name',
            'check_table.check_type_id',
            'check_table.voting_date_start',
            'check_table.voting_date_end',
            'check_table.start_datetime',
            'check_table.end_datetime',
            'check_table.active',
            DB::raw('COUNT(uiks.check_id) as uiks_count'),
            DB::raw('SUM(votes_official.count) as official_votes'),
            DB::raw('SUM(votes.count) as revisor_votes'),
            DB::raw('SUM(inter_votes_official.count) as inter_official_votes'),
            DB::raw('COUNT(uik_camera_count.uik_id) as uik_camera_count'),
            DB::raw('COUNT(uik_videos_processing.uik_id) as uik_videos_processed_count'),
            'videos_processing_false.count as videos_processing_select_false_count',
            'videos_processing_box_true.count as videos_processing_box_true_count',
            'videos_processing_box_false.count as videos_processing_box_false_count',
            'videos_processing_box.count as videos_processing_box_count',
            'videos_processing_counting_true.count as videos_processing_counting_true_count',
            'videos_processing_counting.count as videos_processing_counting_count',
            'videos_processing_counting_false.check_id as videos_processing_counting_false_count',
            'videos_with_recognition_true.videos_count as videos_with_recognition_true_count',
            'videos_with_recognition.videos_count as videos_with_recognition_count',
            DB::raw('COUNT(uik_videos_processing_counting_false.uik_id) as uik_videos_processing_counting_false_count'),
            DB::raw('COUNT(uik_violation.uik_id) as uik_violation_count'),
            'camera_count_with_summary.count as camera_count_with_summary',
            'violations.count as violation_count',
            DB::raw('SUM(votes_deviation.count) as votes_deviation_count'),
            'real_counting_videos.count as real_counting_videos_count',
            'checking_videos.count as checking_videos_count',
            'videos_to_select_cam.count as videos_to_select_cam_count',
            'videos_to_find_boxes.count as videos_to_find_boxes_count',
            'videos_to_count.count as videos_to_count_count',
            'videos_to_real_count.count as videos_to_real_count_count',
            'videos_to_check.count as videos_to_check_count',

            'violation_type_id_1_datetime_null.count as violation_type_id_1_datetime_null_count',
            'violation_type_id_1_datetime_not_null.count as violation_type_id_1_datetime_not_null_count',
            'violation_type_id_4_5.count as violation_type_id_4_5_count',
            'violation_type_id_6_7.count as violation_type_id_6_7_count',
        ];

        $groups = [
            'check_table.check_id',
            'videos_processing_box_true_count',
            'videos_processing_box_count',
            'videos_processing_counting_true_count',
            'videos_processing_counting_count',
            'videos_with_recognition_true_count',
            'videos_with_recognition_count',
            'videos_processing_counting_false_count',
            'camera_count_with_summary',
            'violation_count',
            'real_counting_videos.count',
            'checking_videos.count',
            'videos_to_select_cam.count',
            'videos_to_find_boxes.count',
            'videos_to_count.count',
            'videos_to_real_count.count',
            'videos_to_check.count',
            'videos_processing_false.count',
            'videos_processing_box_false.count',

            'violation_type_id_1_datetime_null.count',
            'violation_type_id_1_datetime_not_null.count',
            'violation_type_id_4_5.count',
            'violation_type_id_6_7.count',
        ];

        return $this->startCondition()
            ->leftJoin('uiks', 'uiks.check_id', '=', 'check_table.check_id')
            ->leftJoinSub(
                $this->subQueryVotesOfficialCount(),
                'votes_official',
                'votes_official.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryVotesUikCount(),
                'votes',
                'votes.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryIntermediateVotesOfficialCount(),
                'inter_votes_official',
                'inter_votes_official.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosProcessingUikCount(true),
                'uik_videos_processing',
                'uik_videos_processing.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosProcessingCount(true, 'boxes'),
                'videos_processing_box_true',
                'videos_processing_box_true.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosProcessingCount(false, 'boxes'),
                'videos_processing_box_false',
                'videos_processing_box_false.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosProcessingCount(false),
                'videos_processing_false',
                'videos_processing_false.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosProcessingCount(null, 'boxes'),
                'videos_processing_box',
                'videos_processing_box.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosProcessingCount(true, 'counting'),
                'videos_processing_counting_true',
                'videos_processing_counting_true.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosProcessingCount(null, 'counting'),
                'videos_processing_counting',
                'videos_processing_counting.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosProcessingCount(false, 'counting'),
                'videos_processing_counting_false',
                'videos_processing_counting_false.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosProcessingUikCount(false, 'counting'),
                'uik_videos_processing_counting_false',
                'uik_videos_processing_counting_false.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryCameraUikCount(),
                'uik_camera_count',
                'uik_camera_count.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryCameraCountGroupByCheckId(true, true),
                'camera_count_with_summary',
                'camera_count_with_summary.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryRecognitionCount(true),
                'videos_with_recognition_true',
                'videos_with_recognition_true.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryRecognitionCount(),
                'videos_with_recognition',
                'videos_with_recognition.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryViolationUikCount(),
                'uik_violation',
                'uik_violation.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryViolationCount(),
                'violations',
                'violations.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryVotesDeviationCount(),
                'votes_deviation',
                'votes_deviation.uik_id',
                '=',
                'uiks.uik_id'
            )
            ->leftJoinSub(
                $this->subQueryRealCountingVideoCount(),
                'real_counting_videos',
                'real_counting_videos.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryCheckingVideoCount(),
                'checking_videos',
                'checking_videos.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosToSelectCamCount(),
                'videos_to_select_cam',
                'videos_to_select_cam.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosToFindBoxCount(),
                'videos_to_find_boxes',
                'videos_to_find_boxes.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosToCountCount(),
                'videos_to_count',
                'videos_to_count.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosToRealCountCount(),
                'videos_to_real_count',
                'videos_to_real_count.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryVideosToCheckCount(),
                'videos_to_check',
                'videos_to_check.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryViolationCount([1], false),
                'violation_type_id_1_datetime_null',
                'violation_type_id_1_datetime_null.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryViolationCount([1], true),
                'violation_type_id_1_datetime_not_null',
                'violation_type_id_1_datetime_not_null.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryViolationCount([1]),
                'violation_type_id_4_5',
                'violation_type_id_4_5.check_id',
                '=',
                'check_table.check_id'
            )
            ->leftJoinSub(
                $this->subQueryViolationCount([1]),
                'violation_type_id_6_7',
                'violation_type_id_6_7.check_id',
                '=',
                'check_table.check_id'
            )
            ->select($columns)
            ->groupBy($groups)
            ->find($id);
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
     * Подзапрос на количество записей в votes (явка ревизора)
     * сгруппированные по uik_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVotesUikCount(): \Illuminate\Database\Eloquent\Builder
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
     * Подзапрос на количество записей в intermediate_votes_official
     * сгруппированные по uik_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryIntermediateVotesOfficialCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
            DB::raw('COUNT(uik_id) as count')
        ];

        $groups = [
            'uik_id'
        ];

        return IntermediateVotesOfficial::select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество uik_id в videos_processing
     * @param bool $processed
     * @param string $taskType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVideosProcessingUikCount(bool $processed = null, string $taskType = 'selecting_cam'): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
        ];

        $groups = [
            'uik_id'
        ];

        $videoProcessing = VideosProcessing::select($columns)
            ->where('task_type', $taskType);

        if ($processed !== null) {
            $videoProcessing->where('processed', $processed);
        }

        return $videoProcessing->groupBy($groups);
    }

    /**
     * Подзапрос на количество строк в videos_processing
     * @param bool $processed
     * @param string $taskType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVideosProcessingCount(bool $processed = null, string $taskType = 'selecting_cam'): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'check_id',
            DB::raw('COUNT(check_id) as count'),
        ];

        $groups = [
            'check_id',
        ];

        $videoProcessing = VideosProcessing::select($columns)
            ->where('task_type', $taskType);

        if ($processed !== null) {
            $videoProcessing->where('processed', $processed);
        }

        return $videoProcessing
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество uik_id в Cameras
     * @param bool|null $main
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryCameraUikCount(bool $main = null): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
        ];

        $groups = [
            'uik_id'
        ];

        $camera = Camera::select($columns);

        if ($main !== null) {
            where('main', $main);
        }

        return $camera->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в Cameras
     * @param bool|null $skip_votes_summary
     * @param bool|null $main
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryCameraCountGroupByCheckId(bool $skip_votes_summary = null, bool $main = null): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'check_id',
            DB::raw('COUNT(check_id) as count'),
        ];

        $groups = [
            'check_id'
        ];

        $camera = Camera::select($columns);

        if ($main !== null) {
            $camera->where('main', true);
        }

        if ($skip_votes_summary !== null) {
            $camera->where('skip_votes_summary', $skip_votes_summary);
        }

        return $camera->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в videos_processing
     * @param bool|null $checked
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryRecognitionCount(bool $checked = null)
    {
        $columns = [
            'videos_processing.check_id',
            DB::raw('COUNT(box_recognitions.video_id) as videos_count'),
        ];

        $groups = [
            'check_id'
        ];

        $videosProcessing = BoxRecognition::select($columns)
            ->where('task_type', 'boxes')
            ->where('processed', true)
            ->leftJoin('videos_processing', 'videos_processing.video_id', '=', 'box_recognitions.video_id');

        if ($checked !== null) {
            $videosProcessing->where('box_recognitions.checked', $checked);
        }

        return $videosProcessing->groupBy($groups);
    }

    /**
     * Подзапрос на количество uik_id в violations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryViolationUikCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
        ];

        $groups = [
            'uik_id'
        ];

        return Camera::select($columns)
            ->join('violations', 'violations.cam_numeric_id', '=', 'cameras.cam_numeric_id')
            ->whereIn('violations.violation_type_id', [6, 7])
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в violations
     * @param array $violationTypeId
     * @param bool $violationDatetimeEnd
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryViolationCount(
        array $violationTypeId = [],
        bool $violationDatetimeEnd = null
    ): \Illuminate\Database\Eloquent\Builder {
        $columns = [
            'check_id',
            DB::raw('COUNT(cameras.check_id) as count'),
        ];

        $groups = [
            'check_id'
        ];

        $camera = Camera::select($columns)
            ->join('violations', 'violations.cam_numeric_id', '=', 'cameras.cam_numeric_id');

        if (count($violationTypeId)) {
            $camera->whereIn('violations.violation_type_id', $violationTypeId);
        }

        if ($violationDatetimeEnd == true) {
            $camera->whereNotNull('violation_datetime_end');
        }

        if ($violationDatetimeEnd == false) {
            $camera->whereNull('violation_datetime_end');
        }

        return $camera->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в votes_deviation
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVotesDeviationCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'uik_id',
            DB::raw('COUNT(uik_id) as count'),
        ];

        $groups = [
            'uik_id'
        ];

        return VotesDeviation::select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в real_counting_videos
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryRealCountingVideoCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'check_id',
            DB::raw('COUNT(check_id) as count'),
        ];

        $groups = [
            'check_id'
        ];

        return RealCountingVideo::select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в checking_videos
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryCheckingVideoCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'check_id',
            DB::raw('COUNT(check_id) as count'),
        ];

        $groups = [
            'check_id'
        ];

        return CheckingVideo::select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в videos_to_select_cam
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVideosToSelectCamCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'check_id',
            DB::raw('COUNT(check_id) as count'),
        ];

        $groups = [
            'check_id'
        ];

        return VideosToSelectCam::select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в videos_to_find_boxes
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVideosToFindBoxCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'check_id',
            DB::raw('COUNT(check_id) as count'),
        ];

        $groups = [
            'check_id'
        ];

        return VideosToFindBox::select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в videos_to_count
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVideosToCountCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'check_id',
            DB::raw('COUNT(check_id) as count'),
        ];

        $groups = [
            'check_id'
        ];

        return VideosToCount::select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в videos_to_real_count
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVideosToRealCountCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'check_id',
            DB::raw('COUNT(check_id) as count'),
        ];

        $groups = [
            'check_id'
        ];

        return VideosToRealCount::select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос на количество записей в videos_to_check
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function subQueryVideosToCheckCount(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'check_id',
            DB::raw('COUNT(check_id) as count'),
        ];

        $groups = [
            'check_id'
        ];

        return VideosToCheck::select($columns)
            ->groupBy($groups);
    }
}
