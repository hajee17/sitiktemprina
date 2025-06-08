<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder {
    public function run(): void {
        $accounts = DB::table('accounts')->pluck('id', 'username');
        $statuses = DB::table('ticket_statuses')->pluck('id', 'name');
        $categories = DB::table('ticket_categories')->pluck('id', 'name');
        $priorities = DB::table('ticket_priorities')->pluck('id', 'name');


        DB::table('tickets')->insert([
            [
                'title' => 'Login error saat input password',
                'description' => 'Setiap kali saya masukkan password, muncul error 500.',
                'account_id' => $accounts['user_achmad'],
                'status_id' => $statuses['open'],
                'category_id' => $categories['Bug'],
                'priority_id' => $priorities['Critical'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Request fitur notifikasi email',
                'description' => 'Saya ingin fitur notifikasi saat tiket dijawab.',
                'account_id' => $accounts['user_achmad'],
                'status_id' => $statuses['in_progress'],
                'category_id' => $categories['Support Teknis'],
                'priority_id' => $priorities['Sedang'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
