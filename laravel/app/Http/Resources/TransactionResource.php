<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public static $format = 'default';
    public function toArray($request)
    {
        switch (TransactionResource::$format) {
            case 'withVcardInformation':
                return [
                    "id" => $this->id,
                    "vcard" => $this->vcard,
                    "vcard_name" => $this->vcard->name,
                    "vcard_email" => $this->vcard->email,
                    "vcard_photo_url" => $this->vcard->photo_url,
                    "date" => $this->date,
                    "datetime" => $this->datetime,
                    "type" => $this->type,
                    "value" => $this->value,
                    "old_balance" => $this->old_balance,
                    "new_balance" => $this->new_balance,
                    "payment_type" => $this->payment_type,
                    "payment_reference" => $this->payment_reference,
                    "pair_transaction" => $this->pair_transaction,
                    "pair_vcard" => $this->pair_vcard,
                    "pair_vcard_name" => $this->pair_vcard->name,
                    "pair_vcard_email" => $this->pair_vcard->email,
                    "pair_vcard_photo_url" => $this->pair_vcard->photo_url,
                    "category_id" => $this->category_id,
                    //"category_name" => $this->category->name,
                    "desciption" => $this->description,
                    //"custom_options" => $this->custom_options,
                    //"custom_data" => $this->custom_data
                    //"transactions" => TransactionResource::collection($this->tasks->sortByDesc('id'))
                ];
            default:
                return [
                    "id" => $this->id,
                    "vcard" => $this->vcard,
                    "date" => $this->date,
                    "datetime" => $this->datetime,
                    "type" => $this->type,
                    // "value" => $this->value,
                    // "old_balance" => $this->old_balance,
                    // "new_balance" => $this->new_balance,
                    "payment_type" => $this->payment_type,
                    "payment_reference" => $this->payment_reference,
                    // "pair_transaction" => $this->pair_transaction,
                    // "pair_vcard_phone_number" => $this->pair_vcard,
                    // "category_id" => $this->category_id,
                    // "desciption" => $this->description,
                    //"custom_options" => $this->custom_options,
                    //"custom_data" => $this->custom_data,
                ];
        }
    }
}
