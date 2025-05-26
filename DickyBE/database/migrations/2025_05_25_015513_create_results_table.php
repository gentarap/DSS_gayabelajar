<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            // Tambah session_id untuk link dengan user_answers
            $table->string('session_id')->after('user_id');

            // Tambah nomor test untuk tracking histori
            $table->integer('test_number')->after('session_id')->default(1);

            // Tambah index untuk performa query
            $table->index(['user_id', 'session_id']);
            $table->index(['user_id', 'test_number']);
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'session_id']);
            $table->dropIndex(['user_id', 'test_number']);
            $table->dropColumn(['session_id', 'test_number']);
        });
    }
};
