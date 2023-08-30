<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\MessageTypeEnum;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileUpdateController extends Controller
{
    /* update user profile */
    public function profileUpdate()
    {
        try {
            return view('frontend.pages.auth.profile_update');
        } catch (Exception $error) {
            Log::info('profileUpdate => frontend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
    /* update user profile */

    /* profile update save */
    public function profileUpdateSave(Request $request)
    {
        $profileUpdateFormData = $request->all();
        $validator = Validator::make($profileUpdateFormData, [
            'user_name' =>  'required|regex:/^[a-zA-Z]/',
        ], [
            'user_name.required' => 'User name is required.',
            'user_name.regex' => 'Invalid user name!',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::WARNING . trans($validator->getMessageBag()->first()));
        } else {
            $user_id = $request->user_id;
            $user = User::find($user_id);

            if (isset($user)) {
                try {
                    $user->user_name = $request->user_name;
                    //$user->last_name = $request->last_name;
                    $user->email = $request->email;
                    $user->mobile_number = $request->mobile_number;
                    if (isset($request->profile_picture)) {
                        if ('APP_ENV' == 'production') {
                            $aws_image_path = Storage::disk('s3')->put('user', $request->profile_picture);
                            $path = Storage::disk('s3')->url($aws_image_path);
                            $pathinfo = pathinfo($path);
                            $q_path = explode("/", $pathinfo['dirname']);
                            $directory =  end($q_path);
                            $filename = $pathinfo['filename'];
                            $extension = $pathinfo['extension'];
                            $imagename = '/' . $directory . '/' . $filename . '.' . $extension;
                            $user->profile_picture = $imagename;
                        } else {
                            $aws_image_path = Storage::disk('s3')->put('user', $request->profile_picture);
                            $path = Storage::disk('s3')->url($aws_image_path);
                            $pathinfo = pathinfo($path);
                            $q_path = explode("/", $pathinfo['dirname']);
                            $directory =  end($q_path);
                            $filename = $pathinfo['filename'];
                            $extension = $pathinfo['extension'];
                            $imagename = '/' . $directory . '/' . $filename . '.' . $extension;
                            $user->profile_picture = $imagename;
                        }
                    }

                    $user->save();
                    //return redirect()->back()->with('success_message', 'Profile updated successfully.');
                    return redirect()->back()->with('TOASTR_MESSAGE', MessageTypeEnum::SUCCESS . trans('Profile updated successfully!'));
                } catch (Exception $e) {
                    Log::info('profileUpdateSave => frontend Error');
                    Log::info($e->getMessage());
                    dd($e->getMessage());
                    return redirect()->back()->with('error_message', 'Something went wrong! Please try again later.');
                }
            }
            return view('/profile');
        }
    }
    /* profile update save */
}
