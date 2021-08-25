<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use App\Http\Requests\User\UserStore;
use App\Http\Requests\User\UserShow;
use App\Http\Requests\User\UserUpdate;
use App\Http\Requests\User\UserDestroy;
use App\Http\Requests\User\UserPassword;

use App\Services\UserService;

use App\Models\User;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users')->with([
            'data' => collect([
                'userList' => $this->userService->getUserList(),
                'roleList' => $this->userService->getRoleList(),
                'permissionList' => $this->userService->getPermissionList(),
                'checkList' => $this->userService->getCheckList(),
            ])
        ]);
    }

    /**
     * Создаем нового(ых) пользователей
     *
     * @param \App\Http\Requests\User\UserStore
     * @return \Illuminate\Support\Collection
     * @throws \League\Csv\InvalidArgument
     */
    public function store(UserStore $request): \Illuminate\Support\Collection
    {
        return $this->userService->userStore($request);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Http\Requests\User\UserShow
     * @return \Illuminate\Support\Collection
     */
    public function show(UserShow $request): \Illuminate\Support\Collection
    {
        return $this->userService->getUserById($request->input('user'));
    }

    /**
     * Редактирование проверки
     *
     * @param \App\Http\Requests\User\UserUpdate
     * @return \Illuminate\Support\Collection
     */
    public function update(UserUpdate $request): \Illuminate\Support\Collection
    {
        return $this->userService->userUpdate($request);
    }

    /**
     * Удаление проверок
     *
     * @param \App\Http\Requests\User\UserUpdate
     * @return \Illuminate\Support\Collection|null
     */
    public function destroy(UserDestroy $request): ?\Illuminate\Support\Collection
    {
        return $this->userService->userDestroy($request);
    }

}
