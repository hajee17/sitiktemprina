<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\TicketCategory;
use App\Models\Sbu;
use App\Models\Department;
use App\Models\Position;
use App\Models\KnowledgeTag;

class LookupDataSeeder extends Seeder
{
    public function run(): void
    {
        // Ticket Statuses
        collect(['Open', 'In Progress', 'On Hold', 'Closed'])->each(fn($name) => TicketStatus::firstOrCreate(['name' => $name]));

        // Ticket Priorities
        TicketPriority::firstOrCreate(['name' => 'Tinggi', 'level' => 1]);
        TicketPriority::firstOrCreate(['name' => 'Sedang', 'level' => 2]);
        TicketPriority::firstOrCreate(['name' => 'Rendah', 'level' => 3]);

        // Ticket Categories
        collect(['Masalah Teknis', 'Pertanyaan Billing', 'Permintaan Fitur', 'Lainnya'])->each(fn($name) => TicketCategory::firstOrCreate(['name' => $name]));

        // SBUs
        collect(['SBU Pengiriman', 'SBU Produksi', 'SBU Pengemasan'])->each(fn($name) => Sbu::firstOrCreate(['name' => $name]));
        
        // Departments
        collect(['IT Support', 'Keuangan', 'Human Resources', 'Operasional'])->each(fn($name) => Department::firstOrCreate(['name' => $name]));

        // Positions
        collect(['Staff', 'Supervisor', 'Manager'])->each(fn($name) => Position::firstOrCreate(['name' => $name]));

        // Knowledge Tags
        collect(['Jaringan', 'Printer', 'Software', 'Akun', 'Login', 'Server'])->each(fn($name) => KnowledgeTag::firstOrCreate(['name' => $name]));
    }
}