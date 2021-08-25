<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationProtocol extends Model
{
    public $primaryKey = 'protocol_id';
    public $timestamps = false;

    protected $fillable = [
        'violation_id',
        'protocol_link',
        'protocol_datetime',
        'response_link',
        'response_datetime',
        'applicant_id',
        'text_type',
        'text_id',
        'decree_id',
    ];
}
