<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketPriority extends Model
{
    protected $fillable = ['name', 'level'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
