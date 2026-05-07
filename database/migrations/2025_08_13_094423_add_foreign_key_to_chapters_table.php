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
        Schema::table('chapters', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change();
            $table->unsignedBigInteger('course_id')->change();

            $table->foreign('course_id')
                ->references('id')
                ->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropForeign(['course_id']);

            $table->integer('id', true)->change();
            $table->integer('course_id')->change();
        });
    }
};
