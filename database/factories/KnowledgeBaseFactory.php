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
        ];
    }

    /**
     * Konfigurasi factory agar otomatis melampirkan tags
     * setelah sebuah KnowledgeBase dibuat.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (KnowledgeBase $knowledgeBase) {
            $tagIds = KnowledgeTag::inRandomOrder()->limit(rand(1, 3))->pluck('id');

            $knowledgeBase->tags()->attach($tagIds);
        });
    }
}