<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //todo fix it
        $data = parent::toArray($request);
        $data['obtain_date'] = Carbon::parse($data['created_at'])->format('Y-m-d');
        unset($data['created_at']);
        $data['title']=$this->course->title;
        unset($data['course']);
        return $data;
    }
}
