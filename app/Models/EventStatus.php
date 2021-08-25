<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventStatus extends Model
{
  protected $primaryKey = 'status_id';
  public $timestamps = false;

  public function events() {
    return $this->hasMany(Event::class,'status_id', 'status_id');
  }
}
