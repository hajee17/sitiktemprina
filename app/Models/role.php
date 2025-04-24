<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'ID_Role';
    public $timestamps = false;

    protected $fillable = ['Role'];

    const DEVELOPER = 1;
    const USER = 2;
}