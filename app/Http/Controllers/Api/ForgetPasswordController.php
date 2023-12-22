<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Notifications\ResetPasswordVerificationNotification;
use Illuminate\Http\Request;


class ForgetPasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequest $request){
        $input = $request->only('email');
        $user = User::where('email', $input)->first();
        $user->notify(new ResetPasswordVerificationNotification());
        $success['success'] = true;
        return response()->json($success, 200);
    }


}
