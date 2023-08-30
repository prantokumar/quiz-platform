<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Http\Requests\PasswordResetFormRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class ForgetPasswordController extends Controller
{
    public function userForgetPassword()
    {
        try {
            return view('frontend.pages.auth.forget_password');
        } catch (Exception $error) {
            Log::info('userUpdatePassword => frontend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    public function userResetPasswordView()
    {
        try {
            return view('frontend.pages.auth.reset_password');
        } catch (Exception $error) {
            Log::info('userResetPasswordView => frontend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    public function forgetPasswordUserCheck(PasswordResetFormRequest $request)
    {
        try {
            $user_details = User::where('user_type', UserTypeEnum::USER);
            if (filter_var($request->email_or_mobile, FILTER_VALIDATE_EMAIL)) {
                $user_details = $user_details->where('email', trim($request->email_or_mobile));
            } else {
                $user_details = $user_details->where('mobile_number', trim($request->email_or_mobile));
            }
            $user_details = $user_details->first();
            if (isset($user_details)) {
                return redirect()->route('userResetPasswordView')->with(['email_or_mobile' => $request->email_or_mobile])->cookie('email_or_mobile_cookie', $request->email_or_mobile, 60);
            } else {
                return redirect()->back()->with('error_message', 'You have no account in this site.');
            }
        } catch (Exception $error) {
            Log::info('forgetPasswordUserCheck => frontend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    public function userResetPasswordSave(Request $request)
    {
        try {
            $email_or_mobile = $request->email_or_mobile;
            //$user = User::find(Auth::user()->id);
            if (!is_null($email_or_mobile)) {
                $user = User::where('user_type', UserTypeEnum::USER);
                if (filter_var($request->email_or_mobile, FILTER_VALIDATE_EMAIL)) {
                    $user = $user->where('email', trim($email_or_mobile));
                } else {
                    $user = $user->where('mobile_number', trim($email_or_mobile));
                }
                $user = $user->first();
                if ($request->password != $request->confirm_password) {
                    return redirect()->back()->with('error_message', 'Ooops! New password & confirm password does not match!.');
                } else {
                    $user->password = bcrypt($request->password);
                    $user->save();
                }
                return redirect()->route('userlogin')->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . trans('Password reset successfully!'));
            } else {
                return redirect()->route('userlogin');
            }
        } catch (Exception $error) {
            Log::info('userResetPasswordSave => frontend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
}
