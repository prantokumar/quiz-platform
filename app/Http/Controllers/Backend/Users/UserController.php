<?php

namespace App\Http\Controllers\Backend\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Enum\UserTypeEnum;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAllRegistedUsers()
    {
        $users = User::where('user_type', UserTypeEnum::USER)->paginate(env('PAGINATION_SMALL'));
        return view('backend.pages.users.users')->with('users', $users);
    }
}
