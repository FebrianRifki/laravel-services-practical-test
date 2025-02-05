<?php

namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    function get_all_course(Request $request){
        try {
            $courses = Course::get();
            return response()->json([
                'status' => true,
                'message' => 'Success fetch courses',
                'code' => 200,
                'data' => $courses
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error!',
                'code' => 500,
                'data' => []
            ]);
        }
    }

    function create_course(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
            ]);
            
            if($validator->fails()){
                // $errors = $validator->errors()->messages();
                return response()->json([
                    "message" => 'please check your input',
                    'success' => false,
                    'code' => 400,
                     'data' => []
                ], 400);
            }

            $course = Course::create([
                'name' => $request->name,
                'description' => $request->description,
                'created_at' => now(),
            ]);

            return response()->json([
                'status' => 'success', 
                'message' => 'course created', 
                'data' => $course
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'success', 
                'message' => 'Internal Server Error! ', 
                'data' => []
            ], 500);
        }
    }

    function get_detail_course(Request $request, $id)
    {
        try {
            $course = Course::find($id);
            return response()->json([
                'status' => true,
                'message' => 'Success feth course detail',
                'code' => 200,
                'data' => $course
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false, 
                'message' => 'Internal Server Error!', 
                'code' => 500, 
                'data' => []
            ], 500);
        }
    }

    function update_course(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
            ]);

            if($validator->fails())
            {
                return response()->json([
                    'status' => false, 
                    'message' => 'Failed to update course', 
                    'code' => 400, 
                    'data' => 'Please check your input'
                ], 400);
            }

            $course = Course::find($id);
            
            if (!$course) {
                return response()->json([
                    'message' => 'Course not found',
                    'success' => false,
                    'code' => 404,
                    'data' => []
                ], 404);
            }

            $course->name = $request->name;
            $course->description = $request->description;
            $course->save();

            return response()->json(
                [
                    'status' => true, 
                    'message' => 'Success update course', 
                    'code' => 200, 
                    'data' => []
                ]
            , 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false, 
                'message' =>  'Internal Server Error! ' . $th, 
                'code' => 500, 
                'data' => []
            ], 500);
        }
    }

    function delete_course(Request $request, $id){
        try {
            $course = Course::find($id);
            $course->delete();

            return response()->json([
                'status' => true, 
                'message' => 'Success delete course', 
                'code' => 200, 
                'data' => []
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false, 
                'message' => 'Internal Server Error!', 
                'code' => 500, 
                'data' => []
            ], 500);
        }
    }
}
