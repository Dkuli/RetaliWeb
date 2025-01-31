<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_template')->default(false);
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained();
            $table->text('question_text');
            $table->string('type');
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('questionnaire_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained();
            $table->foreignId('tour_leader_id')->constrained('tour_leaders');
            $table->dateTime('submitted_at')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_response_id')->constrained();
            $table->foreignId('question_id')->constrained();
            $table->text('answer_text')->nullable();
            $table->json('selected_options')->nullable();
            $table->timestamps();
        });

        Schema::create('questionnaire_tour_leaders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained();
            $table->foreignId('tour_leader_id')->constrained('tour_leaders');
            $table->dateTime('assigned_at');
            $table->dateTime('completed_at')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_answers');
        Schema::dropIfExists('questionnaire_responses');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('questionnaire_tour_leaders');
        Schema::dropIfExists('questionnaires');
    }
};
