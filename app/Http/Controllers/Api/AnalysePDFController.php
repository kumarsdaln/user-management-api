<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalysePDFController extends Controller
{
    public function step1(Request $request){
        $file = $request->file;
        $data = [
            'name' => 'Satendra'
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function step2(Request $request){
        throw new \Exception("API2 failed intentionally");
        $file = $request->file;
        $data = [
            'age' => 25
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function step3(Request $request){
        $file = $request->file;
        $data = [
            'other data' => 'Some Other Data'
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
