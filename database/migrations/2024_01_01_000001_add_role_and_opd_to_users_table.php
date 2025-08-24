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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['asn', 'operator_opd', 'admin'])->default('asn')->after('email');
            $table->string('nip')->unique()->nullable()->after('role');
            $table->string('phone')->nullable()->after('nip');
            $table->foreignId('opd_id')->nullable()->after('phone')->references('id')->on('opds')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['opd_id']);
            $table->dropColumn(['role', 'nip', 'phone', 'opd_id']);
        });
    }
};