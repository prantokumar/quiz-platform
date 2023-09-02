<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface AuthApiInterface
{
    public function checkSecretKeyForApiAuthentication(Request $request);

    public function checkAuthenticateUser(Request $request, $user_id);

    public function checkMobileOrEmailAlreadyExists(Request $request);

    public function user(Request $request);

    public function getUserData(Request $request);

    public function generateSanctumAuthenticationToken(Request $request, $otpVerification);

    public function generateSanctumAuthenticationTokenForTeacher(Request $request, $teacher);

    public function logOutFromSingleDevice(Request $request, $user_id);

    public function logOutFromAllDevices(Request $request, $user_id);
}
