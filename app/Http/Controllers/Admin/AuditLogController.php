<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(): View
    {
        $logs = AuditLog::with(['actor', 'subject'])
            ->latest('created_at')
            ->paginate(25);

        return view('admin.audit-logs', compact('logs'));
    }
}
