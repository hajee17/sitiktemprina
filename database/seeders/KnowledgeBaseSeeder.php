<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KnowledgeBaseSeeder extends Seeder {
    public function run(): void {
        $accountId = DB::table('accounts')->where('username', 'dev_achmad')->value('id');
        $tags = DB::table('knowledge_tags')->pluck('id', 'name');

        DB::table('knowledge_bases')->insert([
            [
                'title' => 'Cara Mengatasi Error Login',
                'content' => 'Jika mengalami error saat login, pastikan password benar dan server aktif.',
                'account_id' => $accountId,
                'tags' => json_encode([$tags['login'], $tags['troubleshooting']]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Langkah Registrasi Akun Baru',
                'content' => 'Klik tombol Register dan isi form dengan email aktif.',
                'account_id' => $accountId,
                'tags' => json_encode([$tags['registration']]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

