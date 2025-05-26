<?php

// Migration untuk update user_answers table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_answers', function (Blueprint $table) {
            // Tambah session_id untuk membedakan setiap sesi test
            $table->string('session_id')->after('user_id');

            // Tambah index untuk performa query
            $table->index(['user_id', 'session_id']);
            $table->index(['session_id']);
        });
    }

    public function down(): void
    {
        Schema::table('user_answers', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'session_id']);
            $table->dropIndex(['session_id']);
            $table->dropColumn('session_id');
        });
    }
};
