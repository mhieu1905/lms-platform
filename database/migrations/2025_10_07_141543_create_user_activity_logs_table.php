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
        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('course_id')->nullable()->index();
            $table->unsignedBigInteger('lesson_id')->nullable()->index();
            $table->enum('action_type', [
                'login', 'logout', 'view', 'pause', 'complete', 'start_course', 'finish_course'
            ]);
            $table->integer('duration')->nullable()->comment('View time (seconds)');
            $table->float('progress_percent')->nullable()->comment('Learning progress (%)');
            $table->string('device_info')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activity_logs');
    }
};
