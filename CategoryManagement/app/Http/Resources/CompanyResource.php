<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'Company ID' => $this->id,
            'Company Name' => $this->name,
            'Company email address' => $this->email,
            'Company phone number' => $this->phone_number,
            'Record created at' => $this->created_at,
            'Record updated at' => $this->updated_at,
        ];
    }
}
