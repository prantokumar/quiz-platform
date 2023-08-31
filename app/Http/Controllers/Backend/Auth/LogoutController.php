<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Traits\SanctumTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    use SanctumTrait;
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            $this->removeAuthenticationToken($request, $user);
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            return redirect('/admin/login');
        } catch (Exception $error) {
            Log::info('logout => Backend Error');
            Log::info($error->getMessage());
            dd($error->getMessage());
            return redirect()->back()->with('message', 'Something went wrong! Please try again later.');
        }
    }
}
