<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  protected $primaryKey = 'event_id';
  public $timestamps = false;

  public function status() {
    return $this->belongsTo(EventStatus::class,'status_id', 'status_id');
  }

  public function result() {
    return $this->belongsTo(CheckResult::class,'event_id', 'event_id');
  }

  public function type() {
    return $this->belongsTo(EventType::class,'event_type_id', 'type_id');
  }

  public function uik() {
    return $this->belongsTo(Uik::class,'uik_id', 'uik_id');
  }
}
