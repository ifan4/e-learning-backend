<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class scoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */


    public function joinFullname($first_name, $last_name)
    {
        $fullname = $first_name;

        if (isset($last_name)) {
            $fullname = $first_name . " " . $last_name;
        }

        return $fullname;
    }

    public function toArray($request): array
    {


        return [
            'id' => $this->id,
            'quiz' => $this->quiz,
            'user' => [
                'id' => $this->user->id,
                'fullname' => $this->joinFullname(
                    $this->user->first_name,
                    $this->user->last_name
                ),
                'nisn' => $this->user->nisn
            ],
            'score' => $this->score,
            'answer' => $this->answer
        ];
    }
}
