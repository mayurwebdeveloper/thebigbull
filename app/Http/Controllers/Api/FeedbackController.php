<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{

    public function store(Request $request){
        
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:30',
            'email' => 'max:30',
            'phone' => 'required|max:20',
            // 'title' => 'required|max:50',
            'description' => 'required',
        ]);

        $currentDate = date('Y-m-d');

        //validation error
        if ($validator->fails()) {
            return response()->json(
                ['status' => 0,
                 'errors' => $validator->errors(),
                 'message' => 'validation error'
                ], 422);
        }


        try {
                $enquiry = new Feedback();
                $enquiry->name = $request->name;
                $enquiry->email = $request->email ?? "";
                $enquiry->phone = $request->phone;
                // $enquiry->title = $request->title;
                $enquiry->description = $request->description;
                $enquiry->issue_date = $currentDate;
                $enquiry->save();
            
        } catch (\Throwable $th) {
           
            return response()->json(
                ['status' => 0,
                 'message' => 'Internal Server Error'
                ], 500);
        }


        //success response
        return response()->json(
            ['status' => 1,
             'errors' => [],
             'message' => 'Your Feedback has beed submited !!!'
            ], 200);

    }

}
