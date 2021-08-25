<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadVideosAuth extends Model
{
  protected $table = 'downloading_videos_auth';
  protected $primaryKey = 'check_id';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'check_id', 'auth_needed', 'auth_type_id', 'auth_link', 'auth_login', 'auth_password'
  ];
}
