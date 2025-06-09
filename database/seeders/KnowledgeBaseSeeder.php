<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KnowledgeBase;

class KnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        KnowledgeBase::factory()->count(15)->create();
    }
}