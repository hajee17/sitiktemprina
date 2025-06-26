<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\TicketComment;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        Ticket::factory()
            ->count(50)
            ->has(TicketComment::factory()->count(3), 'comments')
            ->create();
    }
}