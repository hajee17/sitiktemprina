<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    use HasFactory;
    protected $table = 'ticket_attachments';
    protected $fillable = ['path', 'ticket_id'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}