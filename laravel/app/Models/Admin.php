<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';
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

}
