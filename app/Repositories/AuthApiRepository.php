<?php

namespace App\Repositories;

use App\Http\Controllers\Enum\UserTypeEnum;
use App\Interfaces\AuthApiInterface;
use App\Models\Admin\LoggedInDevice;
use App\Models\User;
use App\UtilityFunction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class AuthApiRepository implements AuthApiInterface
{
    protected $secret = 'c17fedafcf8b0642f178718ba0e9c97544bdccf0';

    public function checkSecretKeyForApiAuthentication(Request $request)
    {
        $secret_key = $this->secret;
        $SecretKey = $request->header('SecretKey');
        if (!is_null($SecretKey)) {
            if ($SecretKey == $secret_key) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function checkAuthenticateUser(Request $request, $user_id)
    {
        $get_header = $request->header('Authorization');
        if (!is_null($get_header)) {
            $header = $request->header('Authorization', $get_header);
            $header_data = $header;
            $replaced_header = Str::replace('Bearer ', '', $header_data);
            [$personal_access_token_id, $auth_token] = explode('|', $replaced_header, 2);
            $personal_access_token = hash('sha256', $auth_token);
            $check_authenticate_user = PersonalAccessToken::where('token', $personal_access_token)->where('tokenable_id', $user_id)->exists();
            return $check_authenticate_user;
        } else {
            return ('APP_ENV' == 'production') ? false : true;
        }
    }

    public function checkMobileOrEmailAlreadyExists(Request $request)
    {
        if (filter_var($request->mobile_or_email, FILTER_VALIDATE_EMAIL)) {
            $checkExistedEmailOrMobile = User::where('user_type', UserTypeEnum::USER)->where('email', $request->mobile_or_email);
        } else {
            $checkExistedEmailOrMobile = User::where('user_type', UserTypeEnum::USER)->where('mobile_number', $request->mobile_or_email);
        }
        $checkExistedEmailOrMobile = $checkExistedEmailOrMobile->exists();

        return $checkExistedEmailOrMobile;
    }

    public function user(Request $request)
    {
        if (filter_var($request->mobile_or_email, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $request->mobile_or_email)->where('user_type', UserTypeEnum::USER);
        } else {
            $user = User::where('mobile_number', $request->mobile_or_email)->where('user_type', UserTypeEnum::USER);
        }
        $user = $user->first();

        return $user;
    }

    public function getUserData(Request $request)
    {
        $user_data = User::select('id', 'user_type', 'user_name', 'email', 'mobile_number', 'otp', 'otp_expired', 'created_at', 'updated_at');
        if (filter_var($request->mobile_or_email, FILTER_VALIDATE_EMAIL)) {
            $user_data = $user_data->where('email', $request->mobile_or_email)->whereNotNull('otp')->first();
        } else {
            $user_data = $user_data->where('mobile_number', $request->mobile_or_email)->whereNotNull('otp')->first();
        }
        return $user_data;
    }

    public function generateSanctumAuthenticationToken(Request $request, $otpVerification)
    {
        $token = $otpVerification->createToken(encrypt($otpVerification->id) . '_Token');
        $token = $token->plainTextToken;
        return $token;
    }

    public function generateSanctumAuthenticationTokenForTeacher(Request $request, $teacher)
    {
        $access_token = PersonalAccessToken::where('tokenable_id', $teacher->id)->first();
        if (isset($access_token)) {
            //Log::info('token already exists');
            $user_id = $access_token->tokenable_id;
            //Log::info('device already exists');
            hash('sha256', $plainTextToken = Str::random(40));
            $personal_access_token = PersonalAccessToken::where('tokenable_id', $user_id)->first();
            $personal_access_token->name = encrypt($teacher->id) . '_Token';
            $personal_access_token->token = hash('sha256', $plainTextToken);
            $personal_access_token->save();
            $token = $personal_access_token->id . '|' . $plainTextToken;
        } else {
            //Log::info('if token does not exists');
            $token = $teacher->createToken(encrypt($teacher->id) . '_Token');
            $token = $token->plainTextToken;
        }
        return $token;
    }

    public function logOutFromSingleDevice(Request $request, $user_id)
    {
        $get_header = $request->header('Authorization');
        if (!is_null($get_header)) {
            $header = $request->header('Authorization', $get_header);
            $header_data = $header;
            $replaced_header = Str::replace('Bearer ', '', $header_data);
            [$personal_access_token_id, $auth_token] = explode('|', $replaced_header, 2);
            $personal_access_token = hash('sha256', $auth_token);
            $authenticate_user = PersonalAccessToken::where('token', $personal_access_token)->where('tokenable_id', $user_id)->first();
            $authenticate_user->delete();
            return $authenticate_user;
        } else {
            return false;
        }
    }

    public function logOutFromAllDevices(Request $request, $user_id)
    {
        $logged_out_all_devices = PersonalAccessToken::where('tokenable_id', $user_id)->delete();
        return $logged_out_all_devices;
    }
}
