<?php

namespace App\Services;

use App\Models\ApiToken;

class ApiTokenService
{

  /**
   * Получаем таблцу с токенами
   *
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   * @return \Illuminate\Support\Collection
   */
  public function getApiTokens()
  {
    return ApiToken::where('token_type', 'perm')->orderBy('creation_datetime')->get();
  }

  /**
   * Получаем токен
   *
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   * @return \Illuminate\Support\Collection
   */
  public function getApiToken($id)
  {
    return ApiToken::find($id);
  }
}
