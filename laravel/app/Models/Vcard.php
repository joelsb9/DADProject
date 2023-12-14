<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vcard extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'phone_number';
    protected $fillable = [
        'phone_number',
        'name',
        'email',
        'photo_url',
        'confirmation_code',
        'blocked',
        'balance',
        'max_debit'
    ];

    protected $casts=[
        'custom_options' => 'json',
        'custom_data' => 'json',
        'blocked' => 'boolean'
    ];
    protected $attributes = [
        'blocked' => false, // Set the default value for 'blocked' to 0
        'balance' => 0, // Set the default value for 'balance' to 0
        'max_debit' => 5000 // Set the default value for 'max_debit' to 0
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'vcard', 'phone_number');
    }

    public function pairTransactions()
    {
        return $this->hasMany(Transaction::class, 'pair_vcard', 'phone_number');
    }
}
