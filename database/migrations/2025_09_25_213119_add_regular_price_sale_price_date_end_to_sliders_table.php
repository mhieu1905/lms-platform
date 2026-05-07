<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->decimal('regular_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->date('date_end')->nullable()->after('sale_price');
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['regular_price', 'sale_price', 'date_end']);
        });
    }
};
