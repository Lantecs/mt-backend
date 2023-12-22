<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{

    function forgetpassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);


        $existingRecord = DB::table('password_resets')->where('email', $request->email)->first();

        if ($existingRecord) {
            // If a record already exists, update the token and created_at timestamp
            $token = Str::random(64);
            DB::table('password_resets')
                ->where('email', $request->email)
                ->update([
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]);
        } else {
            // If no record exists, insert a new one
            $token = str::random(64);
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
        }

        Mail::send("emails.forget-password", ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject("Reset Password");
        });

        $response = [
            "Check your email!"
        ];

        return $token;
    }

    function resetPassword($token)
    {
        return view("new-password", compact('token'));
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users|email',
            'password' => 'required',
            // 'g-recaptcha-response' => 'required|captcha',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }


        $response = [
            'user' => $user,
            'token' => $user->createToken($request->email)->plainTextToken,
        ];

        return $response;
    }

    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'password' => [Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), 'required', 'confirmed',],
            'password_confirmation' => 'required',
            // 'g-recaptcha-response' => 'required|captcha',
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);

        // $data['verification_token'] = Str::random(40);



        $user = User::create($data);

        $token = $user->createToken('authToken')->plainTextToken;

        // if (!$user) {
        //     return redirect(route('registration'))->with("error", "Registration failed, try again.");
        // }

        // Mail::send("emails.verify_email", ['token' => $data['verification_token']], function ($message) use ($request) {
        //     $message->to($request->email);
        //     $message->subject("Verify Email");
        // });

        return ($user);

    }

    public function logOut(Request $request)
    {
        $request->user()->tokens()->delete();

        $response = [
            'message' => 'Logout.'
        ];

        return $response;
    }
}



