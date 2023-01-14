<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Comment\CommentResource;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function($request) {
                return [
                    'id' => $request->id,
                    'user' => $request->user->name,
                    'description' => $request->description,
                    'photo' => config('services.ses.endpoint') . $request->photo,
                    'total_votes' => $request->total_votes,
                    'comments' => [
                        'total' => $request->comments->count(),
                        'data' => CommentResource::collection($request->comments)
                    ]
                ];
            }),
            'meta' => [
                'total' => $this->total(),
                'page' => $this->currentPage(),
                'perPage' => (int) $this->perPage(),
                'totalPages' => $this->lastPage(),
                'path' => $this->path()
            ]
        ];
    }
}
