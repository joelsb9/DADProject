<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'view_auth_users';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    protected $dates = ['email_verified_at'];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'custom_options' => 'json',
        'custom_data' => 'json'
    ];

    public function findForPassport(string $username): User
    {
        return $this->where('username', $username)->first();
    }
}
