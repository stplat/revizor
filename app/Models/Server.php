<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
  public $primaryKey = 'server_id';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'server_id', 'server_type_id', 'online',
    'password', 'server_ip', 'token_id', 'last_response'
  ];

  protected $hidden = [
    'password'
  ];

  public function serverType() {
    return $this->belongsTo(ServerType::class,'server_type_id', 'server_type_id');
  }
}
