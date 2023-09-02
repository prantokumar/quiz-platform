<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\UserStatusEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Http\Requests\LoginFormRequest;
use App\Models\User;
use App\Traits\SanctumTrait;
use App\UtilityFunction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use SanctumTrait;
    protected $loginPath = 'admin/login';
    protected $redirectTo = '/';
    protected $redirectAfterLogout = 'admin/login';
    protected $redirectPath = '/';

    //admin login page view
    public function adminLoginPage()
    {
        try {
            return view('backend.pages.auth.login');
        } catch (Exception $error) {
            Log::info('adminLoginPage => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
    //admin login page view

    //admin login
    public function adminLogin(LoginFormRequest $request)
    {
        try {
            $user_details = User::where('user_type', UserTypeEnum::ADMIN);

            if (filter_var($request->email_or_mobile, FILTER_VALIDATE_EMAIL)) {
                $user_details = $user_details->where('email', trim($request->email_or_mobile));
            } else {
                $user_details = $user_details->where('mobile_number', trim($request->email_or_mobile));
            }
            $user_details = $user_details->first();

            if (isset($user_details)) {
                if (Hash::check($request->password, $user_details->password)) {
                    if ($user_details->user_type == UserTypeEnum::ADMIN) {
                        $this->loginPath = env('REDIRECT_LOCATION_AFTER_SUCCESSFUL_ADMIN_LOGIN');
                        $this->redirectPath = env('REDIRECT_LOCATION_AFTER_SUCCESSFUL_ADMIN_LOGIN');
                        if (filter_var($request->email_or_mobile, FILTER_VALIDATE_EMAIL)) {
                            if (Auth::attempt(['email' => $request->email_or_mobile, 'password' => $request->password, 'user_type' => UserTypeEnum::ADMIN])) {
                                // Generate the authenticated admin user token
                                $this->generateSanctumAuthenticationToken($request, $user_details);
                                return redirect()->intended(env('REDIRECT_LOCATION_AFTER_SUCCESSFUL_ADMIN_LOGIN'));
                            }
                        } else {
                            if (Auth::attempt(['mobile_number' => $request->email_or_mobile, 'password' => $request->password, 'user_type' => UserTypeEnum::ADMIN])) {
                                $this->generateSanctumAuthenticationToken($request, $user_details);
                                return redirect()->intended(env('REDIRECT_LOCATION_AFTER_SUCCESSFUL_ADMIN_LOGIN'));
                            }
                        }
                    } else {
                        return redirect()->back()->with('error_message', 'You are not an authorized member!');
                    }
                } else {
                    return redirect()->back()->with('error_message', 'Invalid mobile number or password');
                }
            }
            return redirect()->back()->with('error_message', 'You have no account in this site.');
        } catch (\Exception $e) {
            Log::info('adminLogin => Backend Error');
            Log::info($e->getMessage());
            dd($e->getMessage());
            return redirect()->back()->with('error_message', 'Something Went Wrong. Please Try Again');
        }
    }
    //admin login
}
