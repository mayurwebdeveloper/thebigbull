<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

class QuizController extends Controller
{
    public function getActiveQuizzes(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'designation' => 'required|integer|exists:designations,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation Error.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the designation id from request
        $designationId = $request->input('designation');

        // Query active quizzes for the given designation
        $quizzes = Quiz::where('designation_id', $designationId)
            ->where('status', '1')
            ->get(['title', 'start_date', 'start_time', 'status']);

        // Format the response data
        $quizList = $quizzes->map(function ($quiz) {
            return [
                'quiz_title' => $quiz->title,
                'quiz_date' => $quiz->start_date,
                'quiz_time' => $quiz->start_time,
                'status' => $quiz->status,
            ];
        });

        return response()->json([
            'status' => 1,
            'message' => 'Active quizzes retrieved successfully',
            'quiz_list' => $quizList
        ]);
    }


    public function getQuizDetails(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|integer|exists:quizzes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation Error.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the quiz id from request
        $quizId = $request->input('quiz_id');

        // Fetch the quiz details
        $quiz = Quiz::with(['questions.answers'])->find($quizId);

        if (!$quiz) {
            return response()->json([
                'status' => 0,
                'message' => 'Quiz not found.'
            ], 404);
        }

        // Format the quiz details
        $questions = $quiz->questions->map(function ($question) {
            return [
                'question_id' => $question->id,
                'question_text' => $question->title,
                'answer_explained' => $question->description,
                'question_options' => $question->answers->map(function ($answer) {
                    return [
                        'option_id' => $answer->id,
                        'option_text' => $answer->title,
                    ];
                })
            ];
        });

        return response()->json([
            'status' => 1,
            'message' => 'Quiz details retrieved successfully',
            'quiz_details' => [
                'quiz_name' => $quiz->title,
                'quiz_datetime' => $quiz->start_date . ' ' . $quiz->start_time,
                'status' => $quiz->status,
                'total_questions' => $quiz->questions->count(),
                'total_time' => $quiz->total_time,
                'questions' => $questions
            ]
        ]);
    }
}
