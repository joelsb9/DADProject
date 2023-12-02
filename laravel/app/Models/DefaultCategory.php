<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DefaultCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "default_categories";
    protected $fillable = [
        'type',
        'name',
        'custom_options',
        'custom_data',
    ];

    protected $dates = ['deleted_at'];
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
}
