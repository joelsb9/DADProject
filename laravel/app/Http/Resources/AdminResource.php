<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    public static $format = 'default';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        switch(AdminResource::$format){
            case 'withTrasactions':
                return [
                    'id' => $this->id,
                    'name' => $this->name,
                    'email' => $this->email,

                ];
            default:
            return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
            ];
        }

    }
}
