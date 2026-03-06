<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    public static function log($action, $targetUserId, $diff = [])
    {
        AuditLog::create([
            'actor_user_id' => Auth::id(),
            'target_user_id' => $targetUserId,
            'action' => $action,
            'payload_diff' => $diff,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}