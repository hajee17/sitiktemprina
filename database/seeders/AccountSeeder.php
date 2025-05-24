<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = DB::table('roles')->pluck('id', 'name');

        DB::table('accounts')->insert([
            ['username' => 'dev_achmad', 'email' => 'dev@example.com', 'password' => Hash::make('password123'), 'role_id' => $roles['developer'], 'profile' => json_encode(['nama' => 'Achmad']), 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'user_achmad', 'email' => 'user@example.com', 'password' => Hash::make('userpass'), 'role_id' => $roles['user'], 'profile' => json_encode(['nama' => 'Rama']), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
