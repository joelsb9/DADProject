<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "categories";
    protected $fillable = [
        'vcard',
        'type',
        'name',
        'custom_options',
        'custom_data',
    ];

    public function getTypeNameAttribute()
    {
        switch ($this->type) {
            case 'C':
                return 'Credit';
            case 'D':
                return 'Debit';
            default:
                return 'Unknown';
        }
    }
    protected $casts = [
        'custom_options' => 'json',
        'custom_data' => 'json'
    ];
    public function vcard()
    {
        return $this->belongsTo(Vcard::class, 'vcard', 'phone_number');
    }
}
