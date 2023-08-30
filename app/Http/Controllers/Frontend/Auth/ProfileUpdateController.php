<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Http\Requests\ProfileUpdateFormRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileUpdateController extends Controller
{
    /* update user profile */
    public function userProfileUpdate()
    {
        try {
            return view('frontend.pages.auth.profile_update');
        } catch (Exception $error) {
            Log::info('userProfileUpdate => frontend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
    /* update user profile */

    /* profile update save */
    public function userProfileUpdateSave(ProfileUpdateFormRequest $request)
    {
        $user_id = Auth::user()->id;
        //$user = User::findOrFail($user_id);
        $user = User::where('user_type', UserTypeEnum::USER)->where('id', $user_id)->first();
        if (isset($user)) {
            try {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->mobile_number = $request->mobile_number;
                if (file_exists($user->photo))
                    unlink($user->photo);
                $path = 'images/users';
                $image = $request->file('photo');
                if (isset($image)) {
                    $fileName = 'user_' . date('Y_m_d_g_i_a_') . $image->getClientOriginalName();
                    $image->move($path . '/', $fileName);
                    $user->photo = $fileName;
                }
                $user->save();
                return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . trans('Profile updated successfully!'));
            } catch (Exception $e) {
                Log::info('userProfileUpdateSave => frontend Error');
                Log::info($e->getMessage());
                dd($e->getMessage());
                return redirect()->back()->with('error_message', 'Something went wrong! Please try again later.');
            }
        }
        return view('/update-profile');
    }
    /* profile update save */
}
