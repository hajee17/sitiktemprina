<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            \App\Models\Account::create([
            'ID_Role' => \App\Models\Role::DEVELOPER,
            'Name' => 'Developer',
            'Email' => 'developer@example.com',
            'Password' => Hash::make('password123'),
            'Telp_Num' => '08123456789'
        ]);
        
           \App\Models\Account::create([
            'ID_Role' => \App\Models\Role::USER,
            'Name' => 'Regular User',
            'Email' => 'user@example.com',
            'Password' => Hash::make('password123'),
            'Telp_Num' => '08123456780'
        ]);

        // Anda bisa menambahkan lebih banyak data di sini
        // ...

        $this->command->info('Account seeding completed!');
    }
}