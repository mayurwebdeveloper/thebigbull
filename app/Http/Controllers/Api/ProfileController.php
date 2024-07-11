<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 0,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        return response()->json([
            'status' => 1,
            'message' => 'Login successful',
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->fname. " " .$user->lname,
                'user_email' => $user->email,
                'user_phone' => $user->mo,
                'avatar' => $user->photo,
                'role' => $user->designation->title,
                'fcm_token' => $user->fcm_token,
            ]
        ]);
    }


    public function editProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 0,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            // 'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'mo' => 'nullable|string|max:15',
            // 'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'designation_id' => 'required|integer|exists:designations,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation Error.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update user profile
        $user->fname = $request->input('fname');
        $user->lname = $request->input('lname');
        // $user->email = $request->input('email');
        $user->mo = $request->input('mo');
        // $user->designation_id = $request->input('designation_id');

        if ($request->hasFile('photo')) {
            // Handle the photo upload
            $file = $request->file('photo');
            $path = $file->store('avatars', 'public');
            $user->photo = $path;
        }

        $user->save();

        return response()->json([
            'status' => 1,
            'message' => 'Profile updated successfully',
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->fname . " " . $user->lname,
                'user_email' => $user->email,
                'user_phone' => $user->mo,
                'avatar' => $user->photo,
                'role' => $user->designation->title,
                'fcm_token' => $user->fcm_token,
            ]
        ]);
    }
}
