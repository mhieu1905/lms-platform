<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('footer_keys')->insertOrIgnore([
            ['name' => 'Main', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Logo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Copyright', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Social', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
