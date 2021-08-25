<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
  public $primaryKey = 'token_id';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'token_id', 'server_id', 'token', 'token_type', 'created_by', 'creation_datetime'
  ];
}
