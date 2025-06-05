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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id('user_answer_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions', 'question_id')->onDelete('cascade');
            $table->foreignId('answer_id')->constrained('answers', 'answer_id')->onDelete('cascade');
            $table->tinyInteger('skor_visual')->default(0);
            $table->tinyInteger('skor_auditory')->default(0);
            $table->tinyInteger('skor_kinestetik')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
