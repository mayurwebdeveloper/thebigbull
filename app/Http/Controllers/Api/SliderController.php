<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\Slide;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        
        // Check if sliders exist
        if ($sliders->isEmpty()) {
            return response()->json(['status' => 0, 'message' => 'No sliders found'], 404);
        }

        // Return success response with sliders data
        return response()->json([
            'status' => 1,
            'data' => $sliders,
        ], 200);
    }



    public function show($code = null)
    {

        if($code){
            $slider = Slider::where('code', $code)->first();
        }else{
            return response()->json(['status' => 0, 'message' => 'Code is required'], 404);
        }

        if (!$slider) {
            return response()->json(['status' => 0, 'message' => 'Slider not found'], 404);
        }

        // Retrieve slides associated with the slider
        $slides = [];
        $result = Slide::where('slider_id', $slider->id)->get();
        if($result){
            foreach($result as $slide){
                if($slide->slide){
                    $slides[] = asset("images/slider/documents/" . $slide->slide);
                }
            }
        }

        $slider->slides = $slides;

        return response()->json([
            'status' => 1,
            'data' => $slider,
        ], 200);
    }


}
