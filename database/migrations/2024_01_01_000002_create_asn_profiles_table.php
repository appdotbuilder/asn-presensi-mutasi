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
        Schema::create('asn_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('full_name')->comment('Full name of the ASN');
            $table->date('birth_date')->nullable()->comment('Birth date');
            $table->string('birth_place')->nullable()->comment('Birth place');
            $table->enum('gender', ['male', 'female'])->comment('Gender');
            $table->text('address')->nullable()->comment('Home address');
            $table->string('position')->nullable()->comment('Current position');
            $table->string('rank')->nullable()->comment('Civil servant rank');
            $table->string('grade')->nullable()->comment('Civil servant grade');
            $table->date('appointment_date')->nullable()->comment('Date of appointment as ASN');
            $table->string('education_level')->nullable()->comment('Highest education level');
            $table->string('major')->nullable()->comment('Education major/field of study');
            $table->text('photo_path')->nullable()->comment('Profile photo path');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('full_name');
            $table->index('position');
            $table->index('rank');
            $table->index('appointment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asn_profiles');
    }
};