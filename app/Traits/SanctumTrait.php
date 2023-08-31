<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;

trait SanctumTrait
{
    /**
     * Set the authenticated user token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User|null
     */
    protected function generateSanctumAuthenticationToken(Request $request, $user)
    {
        $token = $user->createToken(encrypt($user->id) . '_Token');
        $token = $token->plainTextToken;
        return $token;
    }

    /**
     * Remove the authenticated user token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User|null
     */
    protected function removeAuthenticationToken(Request $request, $user)
    {
        return $user->tokens()->delete();
    }

    /**
     * Remove the authenticated user token API request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User|null
     */
    protected function removeAuthenticationTokenFromApiRequest(Request $request, $user_id)
    {
        $get_header = $request->header('Authorization');
        if (!is_null($get_header)) {
            $header = $request->header('Authorization', $get_header);
            $header_data = $header;
            $replaced_header = Str::replace('Bearer ', '', $header_data);
            [$personal_access_token_id, $auth_token] = explode('|', $replaced_header, 2);
            $personal_access_token = hash('sha256', $auth_token);
            $authenticate_user = PersonalAccessToken::where('token', $personal_access_token)->where('tokenable_id', $user_id)->first();
            $authenticate_user->delete();
            return $authenticate_user;
        } else {
            return false;
        }
    }
}
