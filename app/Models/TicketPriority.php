<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPriority extends Model
{
use HasFactory;
protected $table = 'ticket_priorities';
protected $fillable = ['name', 'level'];

public function tickets()
    {
        return $this->hasMany(Ticket::class, 'priority_id');
    }
}