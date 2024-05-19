<?php 
namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class CustomLogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        return response()->json(['message' => 'You Have Successfully Logout'], 200);
    }
}
