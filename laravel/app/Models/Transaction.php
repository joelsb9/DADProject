<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vcard;
use App\Models\Category;
use Illuminate\Database\Eloquent\SoftDeletes;


class Transaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'vcard',
        'date',
        'datetime',
        'type',
        'value',
        'old_balance',
        'new_balance',
        'payment_type',
        'payment_reference',
        'pair_transaction',
        'pair_vcard',
        'category_id',
        'description'
    ];
    protected $dates = ['date','datetime'];
    protected $appends = ['type_name'];
    protected $casts = [
        'datetime' => 'datetime',
        'custom_options' => 'json',
        'custom_data' => 'json'
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

    public function pairTransaction()
    {
        return $this->belongsTo(Transaction::class, 'pair_transaction');
    }

    public function vcard()
    {
        return $this->belongsTo(Vcard::class, 'vcard', 'phone_number');
    }

    public function pairVcard()
    {
        return $this->belongsTo(Vcard::class, 'pair_vcard', 'phone_number');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
