<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public static function log(
        string $action,
        string $module,
        string $description = '',
        ?array $oldData = null,
        ?array $newData = null,
        string $status = 'success'
    ): void {
        $user = Auth::user();

        AuditLog::create([
            'user_id'     => $user?->id,
            'user_name'   => $user?->name,
            'role'        => $user?->role,
            'action'      => $action,
            'module'      => $module,
            'description' => $description,
            'old_data'    => $oldData,
            'new_data'    => $newData,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'status'      => $status,
        ]);
    }
}
