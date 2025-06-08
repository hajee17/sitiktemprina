<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TicketPrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ticket_priorities')->insert([
        ['name' => 'Rendah', 'level' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Sedang', 'level' => 2, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Tinggi', 'level' => 3, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Critical', 'level' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
