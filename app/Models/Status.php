<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'statuses';
    protected $primaryKey = 'ID_Status';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'integer';
    protected $fillable = [
        'ID_Ticket',
        'Update_Time',
        'Status',
        'Desc',
        'Attc',
        'ID_Account',
    ];

    // Relasi ke ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ID_Ticket', 'ID_Ticket');
    }
   
    public function developer()
{
    return $this->belongsTo(Account::class, 'ID_Account', 'ID_Account');
}
   
}