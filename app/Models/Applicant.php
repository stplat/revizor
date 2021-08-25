<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $table = 'violation_applicant_name';
    protected $primaryKey = 'applicant_id';
    public $timestamps = false;

    protected $fillable = [
        'applicant_id', 'name', 'is_organisation'
    ];
}
