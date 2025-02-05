<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Progress;

class ProgressController extends Controller
{

    public function get_all_progress(){
        $progress = Progress::all();
        return  response()->json([
            'message' => 'success fetch progress',
            'status' => true,
            'code' => 200,
            'data' => $progress
        ]);
    }
    public function start(Request $request){
        try {
            Progress::create([
                'course_id' => $request->course_id,
                'user_id' => $request->user_id,
                'status' => 1
            ]);

            return response()->json([
                'message' => 'progress created',
                'status' => true,
                'code' => 200,
                'data' => []
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Internal Server Error',
                'status' => false,
                'code' => 500,
                'data' => []
            ], 500);
        }
    }

    public function complete(Request $request, $id){
        try {
            $progress = Progress::find($id);
            
            if (!$progress) {
                return response()->json([
                    'message' => 'Progress not found',
                    'status' => false,
                    'code' => 404,
                    'data' => []
                ], 404);
            }

            $progress->status = 2;
            $progress->save();
            return response()->json([
                'message' => 'progress updated',
                'status' => true,
                'code' => 200,
                'data' => []
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Internal Server Error ',
                'status' => false,
                'code' => 500,
                'data' => []
            ], 500);
        }
    }
}
