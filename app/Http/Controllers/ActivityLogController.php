<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Display the activity log for tickets.
     */
    public function index()
    {
        // Retrieve all ticket-related activity logs in reverse order
        $logs = ActivityLog::orderBy('created_at', 'desc')->get();
        return view('admin.activitylogs.index', compact('logs'));
    }
}
