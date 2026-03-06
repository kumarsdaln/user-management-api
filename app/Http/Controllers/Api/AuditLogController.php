<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $query = AuditLog::with('actorUser')
            ->latest();

        // Optional filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('target_user_id')) {
            $query->where('target_user_id', $request->target_user_id);
        }

        $logs = $query->paginate(
            $request->per_page ?? 10
        );

        return $this->success('Audit logs retrieved successfully', $logs);
    }
}
