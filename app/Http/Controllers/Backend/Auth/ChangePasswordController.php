<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ChangePasswordController extends Controller
{


    /* change password view */
    public function changePassword()
    {
        try {
            return view('backend.admin.pages.auth.change_password');
        } catch (Exception $error) {
            Log::info('changePassword => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
    /* change password view */

    //save updated password
    public function passwordSave(Request $request)
    {
        try {
            $user = User::find(Auth::user()->id);
            if (isset($user)) {
                if ($request->password != $request->confirm_password) {
                    return redirect()->back()->with('error_message', 'Ooops! New password & confirm password does not match!.');
                }
                //that means the user login as social and now want to set his password
                if (!isset($user->password) || Hash::check($request->current_password, $user->password)) {
                    try {
                        $user->password = bcrypt($request->password);
                        $user->save();
                        return redirect()->back()->with('success_message', 'Password updated successfully.');
                    } catch (\Exception $e) {
                        return redirect()->back()->with('error_message', 'Something went wrong! Please try again later.');
                    }
                }
                return redirect()->back()->with('error_message', 'Provided old password is wrong!');
            }
        } catch (Exception $error) {
            Log::info('passwordSave => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
}
