<?php

namespace App\Repositories;

use App\Interfaces\LeaderBoardApiInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaderBoardApiRepository implements LeaderBoardApiInterface
{

    public function userLeaderBoard()
    {
        $leaderBoard = [];
        $leaderboards = DB::table('exam_submissions')
        ->select('user_id', DB::raw('SUM(obtained_marks) as total_obtained_marks'))
        ->groupBy('user_id')
        ->orderByDesc('total_obtained_marks')
        ->get();
        foreach ($leaderboards as $key => $leaderboard) {
            $collect_leader_board_data = collect($leaderboard);
            $user_id = $leaderboard->user_id;
            $user_name = User::userName($user_id);
            $collect_leader_board_data->put('user_name', $user_name);
            array_push($leaderBoard, $collect_leader_board_data);

        }
        return $leaderBoard;
    }
}
