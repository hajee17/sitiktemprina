<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            ['name' => 'Mesin', 'description' => 'Masalah terkait mesin produksi'],
            ['name' => 'Perangkat Lunak', 'description' => 'Masalah software atau aplikasi'],
            ['name' => 'Perangkat Keras', 'description' => 'Masalah hardware seperti komputer'],
            ['name' => 'Jaringan', 'description' => 'Masalah koneksi internet atau jaringan'],
            ['name' => 'Data', 'description' => 'Masalah terkait database atau kehilangan data'],
            ['name' => 'Support Teknis', 'description' => 'Permintaan bantuan teknis lainnya'],
            ['name' => 'Lainnya', 'description' => 'Masalah lain di luar kategori yang ada'],
        ]);
    }
}
