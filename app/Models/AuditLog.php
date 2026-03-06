<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'actor_user_id',
        'target_user_id',
        'action',
        'payload_diff',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'payload_diff' => 'array'
    ];

    public function actorUser()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
