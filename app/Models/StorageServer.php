<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageServer extends Model
{
    public $primaryKey = 'storage_server_id';
    public $timestamps = false;

    protected $fillable = [
        'check_id',
        'auth_needed',
        'auth_type_id',
        'storage_link',
        'auth_login',
        'auth_password'
    ];
}
