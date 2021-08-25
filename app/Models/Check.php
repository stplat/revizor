<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    protected $table = 'check_table';
    protected $primaryKey = 'check_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'user_login', 'active', 'user_pass', 'creation_date', 'created_by_user_id', 'remember_token', 'role_id'
    ];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uiks()
    {
        return $this->belongsTo(Uik::class, 'check_id', 'check_id');
    }

    public function type()
    {
        return $this->belongsTo(CheckType::class, 'check_type_id', 'check_type_id');
    }

    public function data_type()
    {
        return $this->belongsTo(DataType::class, 'data_type_id', 'data_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function servers()
    {
        return $this->belongsToMany(Server::class, 'check_servers', 'server_id', 'check_id', 'check_id', 'server_id');
    }
}
