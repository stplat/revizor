<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerType extends Model
{
  public $primaryKey = 'server_type_id';
  public $timestamps = false;

  public function servers() {
    return $this->hasMany(Server::class, 'server_type_id');
  }

}
