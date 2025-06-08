<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ticket_categories')->insert([
            ['name' => 'Perangkat Lunak', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Perangkat Keras', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jaringan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Data', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Support Teknis', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bug', 'created_at' => now(), 'updated_at' => now()],
        ]);

    }
}
