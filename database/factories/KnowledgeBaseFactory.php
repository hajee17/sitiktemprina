<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\KnowledgeBase;
use App\Models\KnowledgeTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class KnowledgeBaseFactory extends Factory
{
    protected $model = KnowledgeBase::class;

    public function definition(): array
    {
        $developerIds = Account::whereHas('role', function ($q) {
            $q->where('name', 'developer');
        })->pluck('id');

        return [
            'title' => fake()->sentence(),
            'content' => fake()->text(1000),
            'account_id' => $developerIds->random(),
            // Kolom 'tags' tidak ada lagi di sini
        ];
    }

    /**
     * Konfigurasi factory agar otomatis melampirkan tags
     * setelah sebuah KnowledgeBase dibuat.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (KnowledgeBase $knowledgeBase) {
            // 1. Ambil 1 sampai 3 ID tag secara acak dari database
            $tagIds = KnowledgeTag::inRandomOrder()->limit(rand(1, 3))->pluck('id');

            // 2. Lampirkan tag-tag tersebut ke knowledge base yang baru dibuat
            $knowledgeBase->tags()->attach($tagIds);
        });
    }
}