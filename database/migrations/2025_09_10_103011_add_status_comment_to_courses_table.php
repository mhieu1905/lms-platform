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
        Schema::table('courses', function (Blueprint $table) {
            $table->tinyInteger('status')
                  ->default(0)
                  ->comment('0:pending; 1:publish; 2:hidden')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->tinyInteger('status')
                  ->default(0)
                  ->comment(null)
                  ->change();
        });
    }
};
