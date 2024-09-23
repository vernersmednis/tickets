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
        // Retrieve ticket-related activity logs in reverse order with pagination
        $logs = ActivityLog::orderBy('created_at', 'desc')->paginate(10); // Adjust the number as needed

        return view('admin.activitylogs.index', compact('logs'));
    }
}
