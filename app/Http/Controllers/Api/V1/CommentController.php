<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Comment\StoreRequest;
use App\Http\Requests\Comment\UpdateRequest;
use App\Http\Resources\Comment\CommentResource;

use App\Models\Post;
use App\Models\Comment;

use Auth;

class CommentController extends Controller
{
    public function store(StoreRequest $request)
    {
        $post = Post::where('id', $request->post_id)->first();

        if (!$post) {
            return $this->customResponse('Post not found.', [], Response::HTTP_NOT_FOUND, false);
        }

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'text' => $request->text
        ]);

        return $this->customResponse('Successfully created!', new CommentResource($comment));
    }

    public function update(UpdateRequest $request, Comment $comment)
    {
        if ($comment->user_id != Auth::id()) {
            return $this->customResponse('Comment not found.', [], Response::HTTP_NOT_FOUND, false);
        }

        $comment->update(['text' => $request->text]);

        return $this->customResponse('Successfully updated!', new CommentResource($comment));
    }
}
