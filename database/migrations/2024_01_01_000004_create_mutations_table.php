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
        Schema::create('mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('mutation_number')->unique()->nullable()->comment('Official mutation number');
            $table->enum('type', ['transfer', 'promotion', 'demotion', 'secondment'])->comment('Type of mutation');
            $table->foreignId('from_opd_id')->references('id')->on('opds')->onDelete('cascade');
            $table->foreignId('to_opd_id')->references('id')->on('opds')->onDelete('cascade');
            $table->string('current_position')->comment('Current position');
            $table->string('proposed_position')->comment('Proposed new position');
            $table->string('current_rank')->nullable()->comment('Current rank');
            $table->string('proposed_rank')->nullable()->comment('Proposed new rank');
            $table->text('reason')->comment('Reason for mutation request');
            $table->date('proposed_date')->comment('Proposed effective date');
            $table->enum('status', ['draft', 'submitted', 'opd_review', 'opd_approved', 'opd_rejected', 'bkpsdm_review', 'bkpsdm_approved', 'bkpsdm_rejected', 'completed'])->default('draft')->comment('Mutation status');
            $table->foreignId('opd_reviewed_by')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->timestamp('opd_reviewed_at')->nullable()->comment('OPD review timestamp');
            $table->text('opd_review_notes')->nullable()->comment('OPD review notes');
            $table->foreignId('bkpsdm_reviewed_by')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->timestamp('bkpsdm_reviewed_at')->nullable()->comment('BKPSDM review timestamp');
            $table->text('bkpsdm_review_notes')->nullable()->comment('BKPSDM review notes');
            $table->date('effective_date')->nullable()->comment('Actual effective date');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index('status');
            $table->index('type');
            $table->index('from_opd_id');
            $table->index('to_opd_id');
            $table->index('proposed_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutations');
    }
};