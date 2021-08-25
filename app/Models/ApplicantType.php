<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantType extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'applicant_id', 'name', 'is_organisation'
    ];
}
