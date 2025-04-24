<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $primaryKey = 'ID_Chat';
    public $timestamps = false;

    protected $fillable = [
        'ID_Account',
        'ID_Ticket',
        'Chat'
    ];

    // Relasi ke account
    public function account()
    {
        return $this->belongsTo(Account::class, 'ID_Account', 'ID_Account');
    }

    // Relasi ke ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ID_Ticket', 'ID_Ticket');
    }
}