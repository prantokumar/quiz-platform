<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\UserStatusEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Http\Requests\LoginFormRequest;
use App\Models\User;
use App\UtilityFunction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $loginPath = '/login';
    protected $redirectTo = '/';
    protected $redirectAfterLogout = '/login';
    protected $redirectPath = '/';

    //user login page view
    public function userLoginPage()
    {
        try {
            return view('frontend.pages.auth.login');
        } catch (Exception $error) {
            Log::info('userLoginPage => frontend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
    //user login page view

    //user login
    public function userLogin(LoginFormRequest $request)
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
                if (Hash::check($request->password, $user_details->password)) {
                    if ($user_details->confirmation_code == null) {
                        if ($user_details->user_type == UserTypeEnum::USER) {

                            $this->loginPath = env('REDIRECT_LOCATION_AFTER_SUCCESSFUL_USER_LOGIN');
                            $this->redirectPath = env('REDIRECT_LOCATION_AFTER_SUCCESSFUL_USER_LOGIN');

                            if (filter_var($request->email_or_mobile, FILTER_VALIDATE_EMAIL)) {
                                if (Auth::attempt(['email' => $request->email_or_mobile, 'password' => $request->password, 'user_type' => UserTypeEnum::USER])) {

                                    return redirect()->intended(env('REDIRECT_LOCATION_AFTER_SUCCESSFUL_USER_LOGIN'));
                                }
                            } else {
                                if (Auth::attempt(['mobile_number' => $request->email_or_mobile, 'password' => $request->password, 'user_type' => UserTypeEnum::USER])) {

                                    return redirect()->intended(env('REDIRECT_LOCATION_AFTER_SUCCESSFUL_USER_LOGIN'));
                                }
                            }
                        } else {
                            return redirect()->back()->with('error_message', 'You are not an authorized member!');
                        }
                    } else {
                        return redirect()->back()->with('error_message', 'Please confirm your email.');
                    }
                } else {
                    return redirect()->back()->with('error_message', 'Invalid mobile number or password');
                }
            }
            return redirect()->back()->with('error_message', 'You have no account in this site.');
        } catch (\Exception $e) {
            Log::info('userLogin => frontend Error');
            Log::info($e->getMessage());
            dd($e->getMessage());
            return redirect()->back()->with('error_message', 'Something Went Wrong. Please Try Again');
        }
    }
    //user login
}
