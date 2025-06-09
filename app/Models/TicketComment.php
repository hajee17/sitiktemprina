<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'ticket_id', 'account_id'];

    /**
     * Mendapatkan tiket tempat komentar ini berada.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Mendapatkan akun yang menulis komentar.
     */
    public function author()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}