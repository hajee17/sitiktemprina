<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';
    protected $primaryKey = 'ID_Ticket';
    public $timestamps = true;
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'ID_Ticket',
        'SBU',
        'Dept',
        'Position',
        'Judul_Tiket',
        'Category',
        'Location',
        'Desc',
        'Attc',
        'priority',
        'ID_Account'
    ];

    // Relasi ke status
    public function status()
    {
        return $this->hasOne(Status::class, 'ID_Ticket', 'ID_Ticket');
    }

    // Relasi ke chats
    public function chats()
    {
        return $this->hasMany(Chat::class, 'ID_Ticket', 'ID_Ticket');
    }

    // Relasi ke documentations
    public function documentations()
    {
        return $this->hasMany(Documentation::class, 'ID_Ticket', 'ID_Ticket');
    }

    // Accessor untuk status
    public function getStatusAttribute()
    {
        return $this->status()->first()->Status ?? 'Unknown';
    }

    // Accessor untuk code (format khusus tiket)
    public function getCodeAttribute()
    {
        $categoryPrefix = strtoupper(substr($this->Category, 0, 3));
        return $categoryPrefix . $this->ID_Ticket;
    }

    // Definisikan relasi ke account
    public function account()
    {
        return $this->belongsTo(
            Account::class,
            'ID_Account', // Foreign key di tabel tickets
            'ID_Account' // Primary key di tabel account
        );
    }

    public function index()
{
    // Hitung total semua tiket
    $totalTiket = Ticket::count();

    // Hitung tiket yang masih diproses (status bukan 'Selesai')
    $tiketDiproses = Status::where('Status', '!=', 'Selesai')->distinct('ID_Ticket')->count('ID_Ticket');

    // Hitung tiket yang sudah selesai
    $tiketSelesai = Status::where('Status', 'Selesai')->distinct('ID_Ticket')->count('ID_Ticket');

    return view('dashboard', compact('totalTiket', 'tiketDiproses', 'tiketSelesai'));
    }
    public function getCreatedAtFormattedAttribute()
    {
    return $this->created_at?->isoFormat('D MMM Y HH:mm') ?? '-';
    }
    public function latestStatus() {
    return $this->hasOne(Status::class, 'ID_Ticket')->latest('Update_Time');
    }

    public function tickets()
    {
    return $this->hasMany(Ticket::class, 'ID_Account');
    }

    public function take($id)
{
    // Cari tiket
    $ticket = Ticket::findOrFail($id);
    
    // Validasi bahwa tiket belum diambil
    if ($ticket->status->Status !== 'Baru') {
        return back()->with('error', 'Tiket sudah diambil oleh orang lain');
    }
    
    // Update status tiket
    $ticket->status()->updateOrCreate(
        ['ID_Ticket' => $ticket->ID_Ticket],
        [
            'Status' => 'Diproses',
            'Update_Time' => now(),
            'Desc' => 'Tiket diambil oleh developer',
            'ID_Account' => auth()->id() // ID developer yang mengambil
        ]
    );
    
    return redirect()->route('developer.tickets.index')
        ->with('success', 'Tiket berhasil diambil');
}


// Accessor untuk memudahkan pengecekan
public function getCurrentStatusAttribute()
{
    return optional($this->status)->Status ?? 'Baru';
}

public function statusHistory()
{
    return $this->hasMany(Status::class, 'ID_Ticket', 'ID_Ticket')
                ->orderBy('Update_Time', 'desc');
}
}