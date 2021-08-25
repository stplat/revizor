<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckResult extends Model
{
  protected $primaryKey = 'check_result_id';
  public $timestamps = false;

  public function events() {
    return $this->hasMany(Event::class,'event_id', 'event_id');
  }

  public function user() {
    return $this->belongsTo(User::class,'checked_by', 'user_id');
  }
}
