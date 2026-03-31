<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class AuditTrailController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with(['project', 'actor'])->latest('created_at')->paginate(20);
        return view('admin.audit.index', compact('logs'));
    }
}
