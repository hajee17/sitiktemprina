<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'account';
    protected $primaryKey = 'ID_Account';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'ID_Role',
        'Name',
        'Email',
        'Password',
        'Telp_Num',
        'last_login',
    ];

    protected $hidden = [
        'Password',
        'remember_token',
    ];

    protected $casts = [
        'last_login' => 'datetime',
    ];

    public const ROLE_DEVELOPER = 1;
    public const ROLE_USER = 2;

    // Override kolom email default Laravel

    public function username()
    {
    return 'Email'; 
    }
    public function getAuthIdentifierName()
    {
        return 'Email';
    }

    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'ID_Role', 'ID_Role');
    }

    public function isDeveloper()
    {
        return $this->ID_Role == self::ROLE_DEVELOPER;
    }

    public function isUser()
    {
        return $this->ID_Role == self::ROLE_USER;
    }

    public function updateLastLogin()
    {
        $this->last_login = now();
        $this->save();
    }
}
