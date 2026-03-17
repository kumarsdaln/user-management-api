<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDF extends Model
{
    protected $table = 'p_d_f_s';

    protected $fillable = [
        'pdfpath',
        'status',
        'api1_status',
        'api2_status',
        'api3_status',
        'api1_result',
        'api2_result',
        'api3_result',
        'error_message',
    ];

    protected $casts = [
        'api1_result' => 'array',
        'api2_result' => 'array',
        'api3_result' => 'array',
    ];

    const STATUS_UPLOADED = 'uploaded';
    const STATUS_ANALYSING = 'analysing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_PARTIAL = 'partial_completed';
    const STATUS_FAILED = 'failed';

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_ANALYSING;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isPartial(): bool
    {
        return $this->status === self::STATUS_PARTIAL;
    }

    public function isApi1Success(): bool
    {
        return $this->api1_status === 'success';
    }

    public function isApi2Success(): bool
    {
        return $this->api2_status === 'success';
    }

    public function isApi3Success(): bool
    {
        return $this->api3_status === 'success';
    }
}
