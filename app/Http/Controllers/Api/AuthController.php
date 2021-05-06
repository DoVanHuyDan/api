<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Jobs\SendResetPasswordEmail;

use App\Models\User;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if(!Auth::attempt($credentials)) {
            return response()->json(['success' => false, 'error' => 'Invalid credentials'], 400);
        }

        $token = $request->user()->createToken('Personal Access Token')->accessToken;
        return response()->json([
            'success' => true,
            'access_token' => $token
        ]);
    }

    public function logout()
    {
        try {
            Auth::user()->token()->revoke();
            return response()->json([
                "success" => true
            ]);
        } catch (\Exception $e) {
            Log::error($e);





            return response()->json([
                "success" => false,
                "error" => "Server error"
            ]);
        }
    }

    public function me()
    {
        $user = Auth::user();
        return [
            'success' => true,
            'data' => $user,
        ];
    }

    public function forgotPassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $email = $request->input('email');
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json(['error' => 'Not found'], 404);
            }
            $user->reset_password_token = Str::random();
            $user->save();
            SendResetPasswordEmail::dispatchNow($user);
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::where('reset_password_token', $request->input('token'))->first();
            if (!$user) {
                return response()->json(['error' => 'Invalid token'], 400);
            }
            $accessToken = $user->createToken('Personal Access Token')->accessToken;
            $user->reset_password_token = null;
            $user->save();
            DB::commit();
            return response()->json(['success' => true, 'access_token' => $accessToken]);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
