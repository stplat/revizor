<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
  protected $primaryKey = 'type_id';
  public $timestamps = false;

  public function events() {
    return $this->hasMany(Event::class,'event_id', 'event_id');
  }
}
