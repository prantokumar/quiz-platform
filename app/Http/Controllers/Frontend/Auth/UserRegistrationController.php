<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Http\Requests\RegistrationFormRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserRegistrationController extends Controller
{
    protected $redirectPath = '/login';
    public function register()
    {
        try {
            return view('frontend.pages.auth.register');
        } catch (Exception $error) {
            Log::info('user register => frontend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }

    public function saveNewUser(RegistrationFormRequest $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->user_type = UserTypeEnum::USER;
            if (filter_var($request->email_or_mobile, FILTER_VALIDATE_EMAIL)) {
                $user->email = $request->email_or_mobile;
            } else {
                $user->mobile_number = $request->email_or_mobile;
            }
            $user->password = Hash::make($request->password);
            if ($user->save()) {
                return redirect('/login')->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . trans('Registration Completed Successfully! Now You Can Login.'));
            }else{
                return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
            }
        } catch (Exception $error) {
            Log::info('affiliateRegistrationSave => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
}
