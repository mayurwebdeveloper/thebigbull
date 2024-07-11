<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'unique_id' => 'required|string',
            'password' => 'required|string',
            'fcm_token' => 'required|string',
        ]);

        //validation error
        if ($validator->fails()) {
            return response()->json(
                ['status' => 0,
                 'errors' => $validator->errors(),
                 'message' => 'validation error'
                ], 422);
        }


        $staff = Staff::where('unique_id', $request->unique_id)->first();

        if (! $staff || ! Hash::check($request->password, $staff->password)) {
            return response()->json([
                'status' => 0,
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        $staff->update(['fcm_token' => $request->fcm_token]);

        $token = $staff->createToken('staff-token')->plainTextToken;

        return response()->json([
            'status' => 1,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user_id' => $staff->id,
                'user_name' => $staff->fname. " " .$staff->lname,
                'user_email' => $staff->email,
                'user_phone' => $staff->mo,
                'avatar' => $staff->photo,
                'role' => $staff->designation->title,
                'fcm_token' => $staff->fcm_token,
            ]
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Logged out successfully.'
        ]);
    }
}
