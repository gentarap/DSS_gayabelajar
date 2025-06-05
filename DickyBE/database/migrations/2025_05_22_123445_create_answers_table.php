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
        Schema::create('answers', function (Blueprint $table) {
            $table->id('answer_id');
            $table->foreignId('question_id')->constrained('questions', 'question_id')->onDelete('cascade');
            $table->string('answer_text');
            $table->enum('learning_type', ['visual', 'auditory', 'kinesthetic']);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
