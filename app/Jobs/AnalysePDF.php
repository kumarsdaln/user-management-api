<?php

namespace App\Jobs;

use App\Models\PDF;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AnalysePDF implements ShouldQueue
{
    use Queueable;
    /**
     * Create a new job instance.
     */
    public function __construct(public $pdf_id)
    {
        $this->pdf_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pdf = PDF::find($this->pdf_id);
        if (!$pdf) {
            Log::error("PDF not found: " . $this->pdf_id);
            return;
        }

        $pdf->status = PDF::STATUS_ANALYSING;
        $pdf->save();

        $filePath = Storage::path($pdf->pdfpath);
        if (!file_exists($filePath)) {
            $pdf->status = 'failed';
            $pdf->error_message = 'File not found';
            $pdf->save();
            return;
        }
        $api1Success = false;
        $api2Success = false;
        $api3Success = false;
        try {
            $response = Http::attach(
                'file',
                file_get_contents($filePath),
                'document.pdf'
            )->post(url('/api/pdf/api1'));

            if ($response->successful()) {
                $pdf->api1_status = 'success';
                $pdf->api1_result = $response->json();
                $api1Success = true;
            } else {
                $pdf->api1_status = 'failed';
            }
        } catch (Exception $e) {
            Log::error('API1 error: ' . $e->getMessage());
            $pdf->api1_status = 'failed';
        }

        try {
            $response = Http::attach(
                'file',
                file_get_contents($filePath),
                'document.pdf'
            )->post(url('/api/pdf/api2'));

            if ($response->successful()) {

                $pdf->api2_status = 'success';
                $pdf->api2_result = $response->json();
                $api2Success = true;
            } else {

                $pdf->api2_status = 'failed';
            }
        } catch (Exception $e) {

            Log::error('API2 error: ' . $e->getMessage());
            $pdf->api2_status = 'failed';
        }

        try {
            $response = Http::attach(
                'file',
                file_get_contents($filePath),
                'document.pdf'
            )->post(url('/api/pdf/api3'));

            if ($response->successful()) {

                $pdf->api3_status = 'success';
                $pdf->api3_result = $response->json();
                $api3Success = true;
            } else {
                $pdf->api3_status = 'failed';
            }
        } catch (Exception $e) {
            Log::error('API3 error: ' . $e->getMessage());
            $pdf->api3_status = 'failed';
        }

        if ($api1Success && $api2Success && $api3Success) {
            $pdf->status = PDF::STATUS_COMPLETED;
        } elseif ($api1Success || $api2Success || $api3Success) {
            $pdf->status = PDF::STATUS_PARTIAL;
        } else {
            $pdf->status = PDF::STATUS_FAILED;
        }
        $pdf->save();
    }
}
