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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('date')->comment('Attendance date');
            $table->time('check_in')->nullable()->comment('Check in time');
            $table->time('check_out')->nullable()->comment('Check out time');
            $table->enum('status', ['present', 'absent', 'late', 'sick', 'leave', 'business_trip'])->default('present')->comment('Attendance status');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->comment('Approval status by OPD operator');
            $table->foreignId('approved_by')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->comment('Approval timestamp');
            $table->text('approval_notes')->nullable()->comment('Approval notes');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'date']);
            $table->index('date');
            $table->index('status');
            $table->index('approval_status');
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};