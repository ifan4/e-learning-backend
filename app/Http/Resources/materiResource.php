<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class materiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'file_materi' => $this->file_materi,
            'video_materi' => $this->video_materi,
            'created_at' => $this->created_at,
            'class' => $this->class
        ];
    }
}
