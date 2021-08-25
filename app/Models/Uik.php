<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uik extends Model
{
  protected $primaryKey = 'uik_id';
  public $timestamps = false;

  protected $fillable = [
    'check_id', 'region', 'region_number', 'uik_number', 'address', 'boxes_type', 'timezone_offset', 'voters_registered', 'latitude', 'longitude', 'utc8am', 'uik_type'
  ];

  public function events()
  {
    return $this->hasMany(Event::class, 'uik_id', 'uik_id');
  }

  public function cams()
  {
    return $this->belongsToMany(CamId::class, 'cam_ids', 'uik_id', 'cam_id');
  }

  public function votes()
  {
    return $this->hasMany(Vote::class, 'uik_id', 'uik_id');
  }

  public function official_votes()
  {
    return $this->hasMany(VotesOfficial::class, 'uik_id', 'uik_id');
  }

  public function cameras(){
    return $this->hasMany(Camera::class, 'uik_id', 'uik_id');
  }
}
