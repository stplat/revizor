<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\Check;

use App\Repositories\CheckRepository;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\CannotInsertRecord;
use League\Csv\Writer;

class UserService
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * @var CheckRepository
     */
    protected $checkRepository;

    /**
     * UserService constructor.
     */
    public function __construct()
    {
        $this->userRepository = app(UserRepository::class);
        $this->roleRepository = app(RoleRepository::class);
        $this->checkRepository = app(CheckRepository::class);
    }

    /**
     * Получаем пользователя по id
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getUserById($id): \Illuminate\Support\Collection
    {
        return $this->userRepository->findUserById($id);
    }


    /**
     * Создаем новых пользователей
     * @param object $request
     * @return \Illuminate\Support\Collection
     * @throws \League\Csv\InvalidArgument
     */
    public function userStore(object $request): \Illuminate\Support\Collection
    {
        $users = User::get();
        $faker = app(Faker::class);
        $headersForExport = [];
        $dataForExport = [];
        $createdBy = Auth()->user()->user_id;
        $userQuantity = $request->user_quantity;

        function rndValue($faker, $fakerMethod, $arrayOldValues)
        {
            $value = mb_strtolower($faker->$fakerMethod);

            if (in_array($value, $arrayOldValues)) {
                rndValue($faker, $fakerMethod, $arrayOldValues);
            } else {
                return $value;
            }
        }

        for ($i = 0; $i < $userQuantity; $i++) {
            $user = new User();

            $username = rndValue($faker, 'userName', $users->pluck('username')->toArray());
            $login = rndValue($faker, 'firstName', $users->pluck('user_login')->toArray());
            $password = Str::random(rand(6, 10));
            $currentDate = now()->format('Y-m-d H:i:s');
            $rememberToken = Str::random(10);
            $roleId = $request->role_id;

            $user->username = $username;
            $user->user_login = $login;
            $user->user_pass = Hash::make($password);
            $user->detections_check_access = $request->permissions['detections_check_access'];
            $user->uiks_view_access = $request->permissions['uiks_view_access'];
            $user->events_view_access = $request->permissions['events_view_access'];
            $user->events_form_access = $request->permissions['events_form_access'];
            $user->stats_view_access = $request->permissions['stats_view_access'];
            $user->creation_date = $currentDate;
            $user->created_by_user_id = $createdBy;
            $user->remember_token = $rememberToken;
            $user->role_id = $roleId;
            $user->save();

            if ($roleId == 3) {
                $user->checks()->attach($request->checks);
            }

            array_push($dataForExport, array_values(
                array_merge([
                    'user_id' => $user->user_id,
                    'username' => $username,
                    'user_login' => $login,
                    'user_pass' => $password,
                    'role_name' => Role::find($roleId)->role_name,
                    'role_id' => $roleId,
                ])
            ));

            $headersForExport = array_keys(
                array_merge([
                    'user_id' => $user->user_id,
                    'username' => $username,
                    'user_login' => $login,
                    'user_pass' => $password,
                    'role_name' => Role::find($roleId)->role_name,
                    'role_id' => $roleId,
                ])
            );
        }

        try {
            $name = 'created_users_' . uniqid() . '.csv';

            $csv = Writer::createFromPath(storage_path("app/www/files/$name"), 'w');
            $csv->setOutputBOM(Writer::BOM_UTF8);
            $csv->setDelimiter(';');
            $csv->setNewline("\r\n");
            $csv->insertOne($headersForExport);
            $csv->insertAll($dataForExport);
        } catch (CannotInsertRecord $e) {
            $e->getRecords();
        }

        return collect([
            'userList' => $this->getUserList(),
            'path' => asset("storage/$name")
        ]);
    }

    /**
     * Редактирование пользователя
     * @param object $request
     * @return \Illuminate\Support\Collection
     */
    public function userUpdate(object $request): \Illuminate\Support\Collection
    {
        $user = User::find($request->id);

        $user->username = $request->username;
        $user->user_login = $request->login;

        if ($request->password) {
            $user->user_pass = Hash::make($request->password);
        }

        $user->detections_check_access = $request->permissions['detections_check_access'];
        $user->uiks_view_access = $request->permissions['uiks_view_access'];
        $user->events_view_access = $request->permissions['events_view_access'];
        $user->events_form_access = $request->permissions['events_form_access'];
        $user->stats_view_access = $request->permissions['stats_view_access'];
        $user->role_id = $request->role_id;
        $user->save();

        return $this->getUserList();
    }

    /**
     * Удаление пользователя
     * @param object $request
     * @return \Illuminate\Support\Collection|null
     */
    public function userDestroy(object $request): ?\Illuminate\Support\Collection
    {
        User::destroy($request->user);
        return $this->getUserList();
    }

    /**
     * Получаем пользователей
     *
     * @return \Illuminate\Support\Collection
     */
    public function getUserList(): \Illuminate\Support\Collection
    {
        return $this->userRepository->findUsers();
    }

    /**
     * Получаем список ролей пользователей
     *
     * @return \Illuminate\Support\Collection|null
     */
    public function getRoleList(): \Illuminate\Support\Collection
    {
        return $this->roleRepository->findRoles();
    }

    /**
     * Получаем список всех полномочий
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissionList(): \Illuminate\Support\Collection
    {
        return collect([
            (object)[
                'name' => 'detections_check_access',
                'label' => 'Проверка распознаваний',
                'value' => true
            ],
            (object)[
                'name' => 'uiks_view_access',
                'label' => 'Просмотр участков',
                'value' => true
            ],
            (object)[
                'name' => 'events_view_access',
                'label' => 'Просмотр событий',
                'value' => true
            ],
            (object)[
                'name' => 'events_form_access',
                'label' => 'Формирование жалоб',
                'value' => true
            ],
            (object)[
                'name' => 'stats_view_access',
                'label' => 'Просмотр статистики',
                'value' => true
            ],
        ]);
    }

    /**
     * Получаем список всех проверок
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCheckList(): \Illuminate\Support\Collection
    {
        return $this->checkRepository->findCheckList();
    }
}
