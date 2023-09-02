<?php

namespace App\Http\Controllers\Backend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ExamQuestion;
use App\Models\ExamSubmission;
use App\Models\ExamSubmissionDetail;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    //admin dashboard page view
    public function adminDashboard()
    {
        try {
            return view('backend.pages.dashboard.dashboard');
        } catch (Exception $error) {
            Log::info('adminDashboard => Backend Error');
            Log::info($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
    //admin dashboard page view
}
