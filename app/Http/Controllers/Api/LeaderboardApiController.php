<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AuthApiRepository;
use App\Repositories\LeaderBoardApiRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeaderboardApiController extends Controller
{
    public $AuthApiRepository;
    public $LeaderBoardApiRepository;

    public function __construct(AuthApiRepository $AuthApiRepository, LeaderBoardApiRepository $LeaderBoardApiRepository)
    {
        $this->AuthApiRepository = $AuthApiRepository;
        $this->LeaderBoardApiRepository = $LeaderBoardApiRepository;
    }
    public function getUserLeaderboard(Request $request)
    {
        try {
            if (isset($request->user_id)) {
                if (is_null($request->user_id)) {
                    return $this->noDataFoundApiResponse('User not found!');
                } else {
                    $user_id =  $request->user_id;
                    $authorized = $this->AuthApiRepository->checkAuthenticateUser($request, $user_id);
                    if (!$authorized) {
                        return $this->failedApiResponse('Unauthorized', 401);
                    } else {
                        $check_secret_key = $this->AuthApiRepository->checkSecretKeyForApiAuthentication($request);
                        if (!$check_secret_key) {
                            return $this->failedApiResponse('Unauthorized! Secret Key Missing.', 401);
                        } else {
                            $leader_board = $this->LeaderBoardApiRepository->userLeaderBoard();
                            return $this->successFullApiResponse('Leader Board', $leader_board);
                        }
                    }
                }
            } else {
                return $this->failedApiResponse('User Id missing!', 200);
            }
        } catch (Exception $error) {
            Log::info('studentLogout => API Error');
            Log::error($error->getMessage());
            return $this->errorApiResponse($error->getMessage());
        }
    }
}
