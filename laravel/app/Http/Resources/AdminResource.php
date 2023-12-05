<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    public function boot()
    {
        AdminResource::withoutWrapping();
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'custom_options' => json_decode($this->custom_options),
            'custom_data' => json_decode($this->custom_data),
            'email_verified_at' => $this->email_verified_at,
        ];
    }
}
