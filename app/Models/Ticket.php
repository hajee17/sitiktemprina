<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';
    protected $primaryKey = 'ID_Ticket';
    public $timestamps = false;
    public $incrementing = true; 
    protected $fillable = [
        'SBU',
        'Dept',
        'Position',
        'Judul_Tiket',
        'Category',
        'Location',
        'Desc',
        'Attc',
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
}