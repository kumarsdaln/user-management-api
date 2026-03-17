<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\AnalysePDF;
use App\Models\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadPDFController extends Controller
{
    public function start(Request $request){
        $request->validate([
            'pdf'=>'required|file|mimes:pdf|max:2048'
        ]);
        try {
            $pdfPath = $request->file('pdf')->store('pdfs');
            $pdf = PDF::create([
                'pdfpath' => $pdfPath,
                'status' => PDF::STATUS_UPLOADED
            ]);
            AnalysePDF::dispatch($pdf->id);
            return response()->json([
                'message' => 'PDF uploaded successfully',
                'pdf_id' => $pdf->id,
                'status' => $pdf->status
            ]);
        } catch (\Exception $e) {
            Log::error('PDF Upload Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Something went wrong while uploading PDF'
            ], 500);
        }
    }

    public function status(PDF $pdf){
        return response()->json([
            'status' => $pdf->status,
            'error_message' => $pdf->error_message,
            'api_status' => [
                'api1' => $pdf->api1_status,
                'api2' => $pdf->api2_status,
                'api3' => $pdf->api3_status,
            ]
        ]);
    }

    public function result(PDF $pdf)
    {
        return response()->json([
            'status' => $pdf->status,
            'data' => [
                'api1_result' => $pdf->api1_result,
                'api2_result' => $pdf->api2_result,
                'api3_result' => $pdf->api3_result,
            ]
        ]);
    }
}
