<?php

namespace App\Repositories;

use App\Models\User as Model;

use App\Models\BoxRecognition;
use App\Models\ViolationStatusChange;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получаем перечень констант
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function findUserById($id): \Illuminate\Support\Collection
    {
        $columns = [
            'users.user_id',
            'users.username',
            'users.user_login',
            'users.detections_check_access',
            'users.uiks_view_access',
            'users.events_view_access',
            'users.events_form_access',
            'users.stats_view_access',
            'roles.role_id',
            'check_table.check_id',
            'check_table.check_name',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('roles', 'roles.role_id', '=', 'users.role_id')
            ->leftJoin('check_users', 'check_users.user_id', '=', 'users.user_id')
            ->leftJoin('check_table', 'check_table.check_id', '=', 'check_users.check_id')
            ->where('users.user_id', $id)
            ->get()
            ->groupBy('user_id')
            ->map(function ($item) {
                if (!count($item)) return $item[0];

                $allowedChecks = $item
                    ->groupBy('check_id')
                    ->filter(function ($item, $key) {
                        return $key;
                    })
                    ->map(function ($item, $key) {
                        return collect([
                            'id' => $key,
                            'name' => $item[0]->check_name
                        ]);
                    })
                    ->values();

                return collect([
                    'id' => $item[0]->user_id,
                    'name' => $item[0]->username,
                    'login' => $item[0]->user_login,
                    'role_id' => $item[0]->role_id,
                    'permissions' => collect([
                        (object)[
                            'name' => 'detections_check_access',
                            'label' => 'Проверка распознаваний',
                            'value' => $item[0]->detections_check_access
                        ],
                        (object)[
                            'name' => 'uiks_view_access',
                            'label' => 'Просмотр участков',
                            'value' => $item[0]->uiks_view_access
                        ],
                        (object)[
                            'name' => 'events_view_access',
                            'label' => 'Просмотр событий',
                            'value' => $item[0]->events_view_access
                        ],
                        (object)[
                            'name' => 'events_form_access',
                            'label' => 'Формирование жалоб',
                            'value' => $item[0]->events_form_access
                        ],
                        (object)[
                            'name' => 'stats_view_access',
                            'label' => 'Просмотр статистики',
                            'value' => $item[0]->stats_view_access
                        ],
                    ]),
                    'allowed_checks' => $item[0]->role_id == 3 ? $allowedChecks : []
                ]);
            })
            ->reduce(function ($carry, $item) {
                return $item;
            }, collect());
    }

    /**
     * Получаем перечень констант
     * @return \Illuminate\Support\Collection|null
     */
    public function findUsers(): \Illuminate\Support\Collection
    {
        $columns = [
            'users.user_id',
            'users.username',
            'users.user_login',
            'users.detections_check_access',
            'users.uiks_view_access',
            'users.events_view_access',
            'users.events_form_access',
            'users.stats_view_access',
            'roles.role_id',
            'roles.role_name',
            'check_table.check_id',
            'check_table.check_name',
            'box_recognitions.count as box_recognitions_count',
            'violation_status_changes.count as violation_status_changes_count',
            'user_creators.username as created_by',
        ];

        return $this->startCondition()
            ->select($columns)
            ->leftJoin('roles', 'roles.role_id', '=', 'users.role_id')
            ->leftJoin('check_users', 'check_users.user_id', '=', 'users.user_id')
            ->leftJoin('check_table', 'check_table.check_id', '=', 'check_users.check_id')
            ->leftJoinSub(
                $this->subQueryBoxRecognitionCountGroupByUserId(),
                'box_recognitions',
                'box_recognitions.user_id',
                '=',
                'users.user_id'
            )
            ->leftJoinSub(
                $this->subQueryViolationStatusChangeCountGroupByUserId(),
                'violation_status_changes',
                'violation_status_changes.user_id',
                '=',
                'users.user_id'
            )
            ->leftJoin('users as user_creators', 'users.created_by_user_id', '=', 'user_creators.user_id')
            ->orderBy('users.user_id')
            ->get()
            ->groupBy('user_id')
            ->map(function ($item) {
                if (!count($item)) return $item;

                $fullPermission =
                    $item[0]->detections_check_access &&
                    $item[0]->uiks_view_access &&
                    $item[0]->events_view_access &&
                    $item[0]->events_form_access &&
                    $item[0]->stats_view_access;

                $allowedChecks = $item
                    ->groupBy('check_id')
                    ->filter(function ($item, $key) {
                        return $key;
                    })
                    ->map(function ($item, $key) {
                        return collect([
                            'id' => $key,
                            'name' => $item[0]->check_name
                        ]);
                    })
                    ->values();

                return collect([
                    'id' => $item[0]->user_id,
                    'name' => $item[0]->username,
                    'login' => $item[0]->user_login,
                    'role' => $item[0]->role_name,
                    'box_recognitions_count' => $item[0]->box_recognitions_count,
                    'violation_status_changes_count' => $item[0]->violation_status_changes_count,
                    'created_by' => $item[0]->created_by,
                    'full_permission' => $fullPermission,
                    'permissions' => collect([
                        (object)[
                            'name' => 'detections_check_access',
                            'label' => 'Проверка распознаваний',
                            'value' => $item[0]->detections_check_access
                        ],
                        (object)[
                            'name' => 'uiks_view_access',
                            'label' => 'Просмотр участков',
                            'value' => $item[0]->uiks_view_access
                        ],
                        (object)[
                            'name' => 'events_view_access',
                            'label' => 'Просмотр событий',
                            'value' => $item[0]->events_view_access
                        ],
                        (object)[
                            'name' => 'events_form_access',
                            'label' => 'Формирование жалоб',
                            'value' => $item[0]->events_form_access
                        ],
                        (object)[
                            'name' => 'stats_view_access',
                            'label' => 'Просмотр статистики',
                            'value' => $item[0]->stats_view_access
                        ],
                    ]),
                    'allowed_checks' => $item[0]->role_id == 3 ? $allowedChecks : 'all'
                ]);
            })
            ->values();
    }

    /**
     * Подзапрос с таблицей box_recognitions (проверено распознаваний)
     * return \Illuminate\Database\Query\Builder
     */
    private function subQueryBoxRecognitionCountGroupByUserId(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'checked_by as user_id',
            DB::raw('COUNT(recognition_id) as count')
        ];

        $groups = [
            'checked_by'
        ];

        return BoxRecognition::where('checked', true)
            ->select($columns)
            ->groupBy($groups);
    }

    /**
     * Подзапрос с таблицей violation_status_changes (подано жалоб)
     * return \Illuminate\Database\Query\Builder
     */
    private function subQueryViolationStatusChangeCountGroupByUserId(): \Illuminate\Database\Eloquent\Builder
    {
        $columns = [
            'changed_by as user_id',
            DB::raw('COUNT(change_id) as count')
        ];

        $groups = [
            'changed_by'
        ];

        return ViolationStatusChange::where('status_id', 4)
            ->select($columns)
            ->groupBy($groups);
    }
}
