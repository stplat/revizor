<?php

namespace App\Http\Controllers;

use App\Services\ApiTokenService;
use App\Http\Requests\ApiToken\ApiTokenStore;
use App\Http\Requests\ApiToken\ApiTokenShow;
use App\Http\Requests\ApiToken\ApiTokenUpdate;
use App\Http\Requests\ApiToken\ApiTokenDestroy;

use App\Models\ApiToken;
use App\Models\Role;
use Facade\FlareClient\Api;

class ApiTokenController extends Controller
{
  protected $apiTokenService;

  public function __construct(ApiTokenService $apiTokenService)
  {
    $this->apiTokenService = $apiTokenService;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function index()
  {
    return $this->apiTokenService->getApiTokens();
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
  public function store(ApiTokenStore $request)
  {
    ApiToken::insert([
      'token_id' => $request->input('token_id'),
      'server_id' => $request->input('server_id'),
      'token' => $request->input('token'),
      'token_type' => 'perm',
      'created_by' => Role::where('role_id', auth()->user()->role_id)->first()->role_name,
      'creation_datetime' => now()->format('Y-m-d H:i:s'),
    ]);

    return $this->apiTokenService->getApiTokens();
  }

  /**
   * Display the specified resource.
   *
   * @param \App\Http\Requests\User\UserShow
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function show(ApiTokenShow $request)
  {
    return $this->apiTokenService->getApiToken($request->input('token'));
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
  public function update(ApiTokenUpdate $request)
  {
    ApiToken::find($request->input('token'))->update([
      'token_id' => $request->input('token_id'),
      'server_id' => $request->input('server_id'),
      'token' => $request->input('token_api'),
      'token_type' => 'perm',
      'created_by' => Role::where('role_id', auth()->user()->role_id)->first()->role_name,
      'creation_datetime' => now()->format('Y-m-d H:i:s'),
    ]);

    return $this->apiTokenService->getApiTokens();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Http\Requests\User\UserUpdate
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function destroy(ApiTokenDestroy $request)
  {
    ApiToken::destroy($request->input('token'));
    return $this->apiTokenService->getApiTokens();
  }
}
