<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class KnowledgeTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('knowledge_tags')->insert([
            ['name' => 'login', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'registration', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'troubleshooting', 'created_at' => now(), 'updated_at' => now()],
        ]);

    }
}
