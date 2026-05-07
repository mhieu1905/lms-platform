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
        Schema::table('users', function (Blueprint $table) {
            $table->text('overview')->nullable();
            $table->text('reject_reason')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0:none; 1:pending; 2:approved; 3:rejected');
            $table->string('cv_file')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'overview',
                'reject_reason',
                'status',
                'cv_file',
                'submitted_at',
                'reviewed_at'
            ]);
        });
    }
};
