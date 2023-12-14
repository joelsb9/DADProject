<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TransactionResource;

class VcardResource extends JsonResource
{
    public static $format = 'default';
    public function toArray($request)
    {
        switch (VcardResource::$format) {
            case 'withCategories':
                return [
                    "phone_number" => $this->phone_number,
                    "name" => $this->name,
                    "email" => $this->email,
                    "photo_url" => $this->photo_url,
                    "blocked" => $this->blocked,
                    "balance" => $this->balance,
                    "max_debit" => $this->max_debit,
                    'custom_options' => json_decode($this->custom_options),
                    'custom_data' => json_decode($this->custom_data),
                    "categories" => CategoryResource::collection($this->categories()->orderBy('id', 'desc')->get()),
                ];
            case 'withTrasactions':
                return [
                    "phone_number" => $this->phone_number,
                    "name" => $this->name,
                    "email" => $this->email,
                    "photo_url" => $this->photo_url,
                    "blocked" => $this->blocked,
                    "balance" => $this->balance,
                    "max_debit" => $this->max_debit,
                    'custom_options' => json_decode($this->custom_options),
                    'custom_data' => json_decode($this->custom_data),
                    "transactions" => TransactionResource::collection($this->transactions()->orderBy('id', 'desc')->get()),

                ];
            default:
                return [
                    "phone_number" => $this->phone_number,
                    "name" => $this->name,
                    "email" => $this->email,
                    "photo_url" => $this->photo_url,
                    "blocked" => $this->blocked,
                    "balance" => $this->balance,
                    "max_debit" => $this->max_debit,
                    'custom_options' => json_decode($this->custom_options),
                    'custom_data' => json_decode($this->custom_data),
                ];
        }
    }
}
