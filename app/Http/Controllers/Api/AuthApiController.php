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
    public $GlobalConfigRepository;
    public $StudentRegistrationRepositoty;
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
            } else {
                $user = $this->AuthApiRepository->user($request);
                if (isset($user)) {
                    if (Hash::check($request->password, $user->password)) {
                        if ($user->confirmation_code == null) {
                            if ($user->user_type == UserTypeEnum::ADMIN) {

                                if (filter_var($request->email_or_mobile, FILTER_VALIDATE_EMAIL)) {
                                    if (Auth::attempt(['email' => $request->email_or_mobile, 'password' => $request->password, 'user_type' => UserTypeEnum::ADMIN])) {
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
                                    if (Auth::attempt(['mobile_number' => $request->email_or_mobile, 'password' => $request->password, 'user_type' => UserTypeEnum::ADMIN])) {
                                        $this->generateSanctumAuthenticationToken($request, $user);
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
                                }
                            } else {
                                return $this->failedApiResponse('You are not an authorized member!', 200);
                            }
                        } else {
                            return $this->failedApiResponse('Please confirm your email.', 200);
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
}
