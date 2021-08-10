<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MMonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\MMonDB::factory()->count(30)->create();
    }
}
