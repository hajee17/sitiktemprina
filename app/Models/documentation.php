<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    use HasFactory;

    protected $table = 'documentations';
    protected $primaryKey = 'ID_Doc';
    public $timestamps = false;

    protected $fillable = [
        'ID_Role',
        'ID_Ticket',
        'Judul',
        'Category',
        'Desc',
        'Text',
        'Attc'
    ];

    // Relasi ke role
    public function role()
    {
        return $this->belongsTo(Role::class, 'ID_Role', 'ID_Role');
    }

    // Relasi ke ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ID_Ticket', 'ID_Ticket');
    }
}