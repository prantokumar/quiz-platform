<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Repositories\AuthApiRepository;
use App\Traits\SanctumTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthApiController extends Controller
{
    use SanctumTrait;
    public $AuthApiRepository;

    public function __construct(AuthApiRepository $AuthApiRepository)
    {
        $this->AuthApiRepository = $AuthApiRepository;
    }
    public function adminUserLogin(Request $request)
    {
        try {
            if (is_null($request->mobile_or_email)) {
                return $this->validateDataApiResponse('Mobile number or email is required*');
            } else if (is_null($request->password)) {
                return $this->validateDataApiResponse('Password is required*');
            } else {
                $user = $this->AuthApiRepository->user($request);
                if (isset($user)) {
                    if (Hash::check($request->password, $user->password)) {
                        if ($user->user_type == UserTypeEnum::ADMIN) {
                            if (filter_var($request->email_or_mobile, FILTER_VALIDATE_EMAIL)) {
                                $token = $this->AuthApiRepository->generateSanctumAuthenticationToken($request, $user);
                                return response()->json([
                                    'success' => true,
                                    'message_type' => 'success',
                                    'auth_credential' => encrypt($user->id),
                                    'auth_token' => $token,
                                    'user_details' => $user,
                                    'code' => response()->json()->status(),
                                ]);
                            } else {
                                $token = $this->AuthApiRepository->generateSanctumAuthenticationToken($request, $user);
                                return response()->json([
                                    'success' => true,
                                    'message_type' => 'success',
                                    'auth_credential' => encrypt($user->id),
                                    'auth_token' => $token,
                                    'user_details' => $user,
                                    'code' => response()->json()->status(),
                                ]);
                            }
                        } else {
                            return $this->failedApiResponse('You are not an authorized member!', 200);
                        }
                    } else {
                        return $this->failedApiResponse('Invalid mobile number or password', 200);
                    }
                } else {
                    return $this->failedApiResponse('Wrong email or phone!', 200);
                }
            }
        } catch (Exception $error) {
            Log::info('adminUserLogin => API Error');
            Log::error($error->getMessage());
            return $this->errorApiResponse($error->getMessage());
        }
    }

    public function adminUserLogout(Request $request)
    {
        try {
            if (isset($_GET['user_id'])) {
                if (is_null($_GET['user_id'])) {
                    return $this->noDataFoundApiResponse('User not found!');
                } else {
                    $user_id =  $_GET['user_id'];
                    $authorized = $this->AuthApiRepository->checkAuthenticateUser($request, $user_id);
                    if (!$authorized) {
                        return $this->failedApiResponse('Unauthorized', 401);
                    } else {
                        $check_secret_key = $this->AuthApiRepository->checkSecretKeyForApiAuthentication($request);
                        if (!$check_secret_key) {
                            return $this->failedApiResponse('Unauthorized! Secret Key Missing.', 401);
                        } else {
                            $this->AuthApiRepository->logOutFromSingleDevice($request, $user_id);
                            return $this->successResponse('Logged out successfully!');
                        }
                    }
                }
            } else {
                return $this->failedApiResponse('Data missing!', 200);
            }
        } catch (Exception $error) {
            Log::info('studentLogout => API Error');
            Log::error($error->getMessage());
            return $this->errorApiResponse($error->getMessage());
        }
    }
}
