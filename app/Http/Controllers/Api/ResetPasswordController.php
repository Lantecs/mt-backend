<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Notifications\ResetPasswordVerificationNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{

    public function passwordReset(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users|email',
            'otp' => 'required|numeric|digits:6',
            'password' => [Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), 'required', 'confirmed',],
            'password_confirmation' => 'required',
        ]);

        $otpInstance = new Otp;

        $otp = $otpInstance->validate($request->email, $request->otp);

        if (!$otp->status) {
            return response()->json(['error' => $otp], 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Update the user's password using the update method
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $success['success'] = true;
        return response()->json($success, 200);
    }


}


// 'password' => [Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), 'required', 'confirmed',],
