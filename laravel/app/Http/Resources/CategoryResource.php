<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public static $format = 'default';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        switch(CategoryResource::$format){
            case 'withVcardInformation':
                return [
                    'id' => $this->id,
                    'vcard' => $this->vcard,
                    'vcard_info' => new VcardResource($this->vcard),
                    'type' => $this->type,
                    'name' => $this->name,
                    'custom_options' => json_decode($this->custom_options),
                    'custom_data' => json_decode($this->custom_data),
                    'deleted_at' => $this->deleted_at,
            ];
            default:
            return [
                'id' => $this->id,
                'vcard' => $this->vcard,
                'type' => $this->type,
                'name' => $this->name,
                'custom_options' => json_decode($this->custom_options),
                'custom_data' => json_decode($this->custom_data),
                'deleted_at' => $this->deleted_at,
            ];
        }
    }
}
