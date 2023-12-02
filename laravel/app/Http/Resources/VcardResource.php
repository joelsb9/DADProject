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
            case 'withTrasactions':
                return [
                    "phone_number" => $this->phone_number,
                    "name" => $this->name,
                    "email" => $this->email,
                    "photo_url" => $this->photo_url,
                    "blocked" => $this->blocked,
                    "balance" => $this->balance,
                    "max_debit" => $this->max_debit,
                    "custom_options" => $this->custom_options,
                    "custom_data" => $this->custom_data,
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
                    "custom_options" => $this->custom_options,
                    "custom_data" => $this->custom_data,
                ];
        }
    }
}
