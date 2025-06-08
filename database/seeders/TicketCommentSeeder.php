<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketCommentSeeder extends Seeder {
    public function run(): void {
        $accounts = DB::table('accounts')->pluck('id', 'username');
        $tickets = DB::table('tickets')->pluck('id', 'title');

        DB::table('ticket_comments')->insert([
            [
                'ticket_id' => $tickets['Login error saat input password'],
                'account_id' => $accounts['dev_achmad'],
                'message' => 'Halo, apakah error terjadi setelah update terakhir?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ticket_id' => $tickets['Request fitur notifikasi email'],
                'account_id' => $accounts['dev_achmad'],
                'message' => 'Fitur sedang dalam tahap pengembangan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
