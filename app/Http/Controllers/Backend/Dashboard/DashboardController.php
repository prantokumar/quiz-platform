<?php

namespace App\Http\Controllers\Backend\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\ExamEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamSubmission;
use App\Models\ExamSubmissionDetail;
use App\Models\Question;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    //admin dashboard page view
    public function adminDashboard()
    {
        try {
            $total_users = User::where('user_type', UserTypeEnum::USER)->get()->count();
            $exams = Exam::where('status', ExamEnum::ACTIVE)->get()->count();
            $questions = Question::where('status', 1)->get()->count();
            $submissions = ExamSubmission::where('status', 1)->get()->count();
            return view('backend.pages.dashboard.dashboard')->with(['total_users'=> $total_users, 'exams'=> $exams, 'questions' => $questions, 'submissions' => $submissions]);
        } catch (Exception $error) {
            Log::info('adminDashboard => Backend Error');
            Log::info($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
    //admin dashboard page view
}
