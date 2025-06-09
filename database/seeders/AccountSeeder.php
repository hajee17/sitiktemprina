<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::factory()->count(20)->create([
            'role_id' => Role::where('name', 'user')->first()->id,
        ]);
        $roles = DB::table('roles')->pluck('id', 'name');

        DB::table('accounts')->insert([
            [
                'username' => 'dev_achmad',
                'email' => 'dev@example.com',
                'password' => Hash::make('password123'),
                'role_id' => $roles['developer'],
                'name' => 'Achmad',
                'phone' => '081234567890',
                'address' => 'Jl. Developer No.1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'user_achmad',
                'email' => 'user@example.com',
                'password' => Hash::make('userpass'),
                'role_id' => $roles['user'],
                'name' => 'Rama',
                'phone' => '089876543210',
                'address' => 'Jl. Pengguna No.2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
