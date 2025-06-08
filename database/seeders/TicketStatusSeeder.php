<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ticket_statuses')->insert([
            ['name' => 'open', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'in_progress', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'closed', 'created_at' => now(), 'updated_at' => now()],
        ]);

    }
}
