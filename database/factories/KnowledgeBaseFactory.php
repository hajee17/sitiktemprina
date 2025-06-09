<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\KnowledgeTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class KnowledgeBaseFactory extends Factory
{
    protected $model = \App\Models\KnowledgeBase::class;

    public function definition(): array
    {
        $tags = KnowledgeTag::inRandomOrder()->limit(rand(1, 3))->pluck('name');

        $developerIds = Account::whereHas('role', function ($q) {
            $q->where('name', 'developer'); 
        })->pluck('id');

        return [
            'title' => fake()->sentence(),
            'content' => fake()->text(1000),
            'account_id' => $developerIds->random(), 
            'tags' => $tags,
        ];
    }
}