<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Comment\CommentResource;

class PostResource extends JsonResource
{
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
            'description' => $this->description,
            'photo' => $this->photo,
            'total_votes' => $this->total_votes,
            'comments' => [
                'total' => $this->comments->count(),
                'data' => CommentResource::collection($this->comments)
            ]
        ];
    }
}
