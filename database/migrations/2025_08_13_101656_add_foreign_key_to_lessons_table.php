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
        Schema::table('lessons', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->change();
            $table->unsignedBigInteger('chapter_id')->change();

            $table->foreign('chapter_id')
                ->references('id')
                ->on('chapters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['chapter_id']);

            $table->integer('id', true)->change();
            $table->integer('chapter_id')->change();
        });
    }
};
