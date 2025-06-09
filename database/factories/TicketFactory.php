<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\SBU;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Ticket::class;
    
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(3),
            'account_id' => Account::inRandomOrder()->first()->id,
            'status_id' => TicketStatus::inRandomOrder()->first()->id,
            'category_id' => TicketCategory::inRandomOrder()->first()->id,
            'priority_id' => TicketPriority::inRandomOrder()->first()->id,
            'sbu_id' => SBU::inRandomOrder()->first()->id,
            'department_id' => Department::inRandomOrder()->first()->id,
        ];
    }
}