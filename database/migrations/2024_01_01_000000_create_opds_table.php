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
        Schema::create('opds', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Name of the government agency');
            $table->string('code')->unique()->comment('Agency code');
            $table->text('description')->nullable()->comment('Agency description');
            $table->string('address')->nullable()->comment('Agency address');
            $table->string('phone')->nullable()->comment('Agency phone number');
            $table->string('email')->nullable()->comment('Agency email');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Agency status');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('name');
            $table->index('code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opds');
    }
};