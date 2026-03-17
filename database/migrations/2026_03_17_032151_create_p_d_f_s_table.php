<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('p_d_f_s', function (Blueprint $table) {
            $table->id();
            $table->string('pdfpath');
            $table->string('status')->default('uploaded');
        
            $table->string('api1_status')->nullable();
            $table->string('api2_status')->nullable();
            $table->string('api3_status')->nullable();

            $table->json('api1_result')->nullable();
            $table->json('api2_result')->nullable();
            $table->json('api3_result')->nullable();
        
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_d_f_s');
    }
};
