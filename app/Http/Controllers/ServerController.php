<?php

namespace App\Http\Controllers;

use App\Services\ServerService;
use App\Http\Requests\Server\ServerStore;
use App\Http\Requests\Server\ServerShow;
use App\Http\Requests\Server\ServerUpdate;
use App\Http\Requests\Server\ServerDestroy;

use App\Models\Server;
use App\Services\TableService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ServerController extends Controller
{
  protected $serverService;

  public function __construct(ServerService $serverService)
  {
    $this->serverService = $serverService;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function index()
  {
    return $this->serverService->getAll();
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \App\Http\Requests\User\UserStore
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function store(ServerStore $request)
  {
    return $this->serverService->store(
      [
        'server_type_id' => $request->input('server_type_id')
      ],
      $request->input('qty'),
      true
    );
  }

  /**
   * Display the specified resource.
   *
   * @param \App\Http\Requests\User\UserShow
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function show(ServerShow $request)
  {
    return $this->serverService->getOne($request->input('server'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \App\Http\Requests\User\UserUpdate
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function update(ServerUpdate $request)
  {
    Server::find($request->input('server'))->update([
      'server_id' => $request->input('server_id'),
      'password' => Hash::make($request->input('password')),
    ]);

    return $this->serverService->getAll();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Http\Requests\User\UserUpdate
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function destroy(ServerDestroy $request)
  {
    ApiToken::destroy($request->input('token'));
    return $this->serverService->getApiTokens();
  }

  /**
   * Display available server types.
   *
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function indexServerTypes()
  {
    return $this->serverService->getServerTypes();
  }
}
