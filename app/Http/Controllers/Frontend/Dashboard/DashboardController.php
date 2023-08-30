<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    //admin dashboard page view
    public function userDashboard()
    {
        try {
            return view('frontend.pages.dashboard.dashboard');
        } catch (Exception $error) {
            Log::info('userDashboard => frontend Error');
            Log::info($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
    //admin dashboard page view
}
