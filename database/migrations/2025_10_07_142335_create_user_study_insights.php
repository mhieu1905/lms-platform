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
        Schema::create('user_study_insights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique()->index();
            $table->integer('total_courses')->default(0);
            $table->integer('completed_courses')->default(0);
            $table->float('avg_progress')->default(0);
            $table->dateTime('last_activity_at')->nullable();
            $table->integer('inactive_days')->default(0);
            $table->float('risk_score')->default(0);
            $table->dateTime('last_email_sent_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_study_insights');
    }
};
