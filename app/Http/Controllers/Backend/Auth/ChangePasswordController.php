<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Http\Requests\PasswordUpdateFormRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ChangePasswordController extends Controller
{
    /* change password view */
    public function adminUpdatePassword()
    {
        try {
            return view('backend.pages.auth.change_password');
        } catch (Exception $error) {
            Log::info('adminUpdatePassword => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
    /* change password view */

    //save updated password
    public function adminUpdatePasswordSave(PasswordUpdateFormRequest $request)
    {
        try {
            $user_id = Auth::user()->id;
            //$user = User::find(Auth::user()->id);
            $user = User::where('user_type', UserTypeEnum::ADMIN)->where('id', $user_id)->first();
            if (isset($user)) {
                if ($request->password != $request->confirm_password) {
                    return redirect()->back()->with('error_message', 'Ooops! New password & confirm password does not match!.');
                }
                if (!isset($user->password) || Hash::check($request->current_password, $user->password)) {
                    try {
                        $user->password = bcrypt($request->password);
                        $user->save();
                        return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . trans('Password updated successfully!'));
                    } catch (\Exception $e) {
                        return redirect()->back()->with('error_message', 'Something went wrong! Please try again later.');
                    }
                }
                return redirect()->back()->with('error_message', 'Provided old password is wrong!');
            }
        } catch (Exception $error) {
            Log::info('adminUpdatePasswordSave => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
    //save updated password
}
