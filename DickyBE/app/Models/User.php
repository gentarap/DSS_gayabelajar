<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, CanResetPassword;

    // Tentukan primary key jika tidak menggunakan 'id'
    protected $primaryKey = 'user_id';

    // Jika primary key bukan integer auto-increment, set ini ke false
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'api_token',
    ];

    public function answers()
    {
        return $this->hasMany(UserAnswer::class, 'user_id', 'user_id');
    }

    public function result()
    {
        return $this->hasOne(Result::class, 'user_id', 'user_id');
    }

    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
