<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaderBoardController extends Controller
{
    public function userLeaderBoard()
    {
        try {
            $leaderboards = DB::table('exam_submissions')
                ->select('user_id', DB::raw('SUM(obtained_marks) as total_obtained_marks'))
                ->groupBy('user_id')
                ->orderByDesc('total_obtained_marks')
                ->paginate(env('PAGINATION_SMALL'));
            return view('frontend.pages.leaderboard.leaderboard')->with('leaderboards', $leaderboards);
        } catch (Exception $error) {
            Log::info('userLeaderBoard => frontend Error');
            Log::info($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
    public function LeaderBoard()
    {
        try {
            $leaderboards = DB::table('exam_submissions')
                ->select('user_id', DB::raw('SUM(obtained_marks) as total_obtained_marks'))
                ->groupBy('user_id')
                ->orderByDesc('total_obtained_marks')
                ->paginate(env('PAGINATION_SMALL'));
            return view('backend.pages.leaderboard.leaderboard')->with('leaderboards', $leaderboards);
        } catch (Exception $error) {
            Log::info('userLeaderBoard => frontend Error');
            Log::info($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
}
