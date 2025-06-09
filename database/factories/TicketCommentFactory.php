<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\TicketComment::class;

    public function definition(): array
    {
        return [
            'message' => fake()->paragraph(2),
            // ID akun dan tiket akan di-supply saat seeder berjalan
            'account_id' => Account::inRandomOrder()->first()->id,
            'ticket_id' => Ticket::inRandomOrder()->first()->id,
        ];
    }
}