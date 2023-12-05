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
    protected $dates = ['date','datetime','created_at', 'updated_at','deleted_at'];

    protected $casts = [
        //'date' => 'date',
        'datetime' => 'datetime'
    ];

    //if we need to set default values for date and datetime
    // protected static function boot()
    // {
    //     parent::boot();

    //     // Use the 'creating' event to automatically set the 'date' and 'datetime' columns
    //     static::creating(function ($transaction) {
    //         // Set the 'date' column to the current date
    //         $transaction->date = Carbon::now()->toDateString();

    //         // Set the 'datetime' column to the current date and time
    //         $transaction->datetime = Carbon::now();
    //     });
    // }

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
        return $this->belongsTo(Category::class);
    }
}
