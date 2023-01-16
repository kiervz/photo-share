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
    /**
     * Store a newly created Comment
     *
     * @param  App\Http\Requests\Comment\StoreRequest $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified Comment
     *
     * @param  App\Http\Requests\Comment\UpdateRequest $request
     * @param  App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Comment $comment)
    {
        /**
         * Return 404 Page Not Found if the Comment's user_id
         * is not equal to logged in user's id.
         */
        if ($comment->user_id != Auth::id()) {
            return $this->customResponse('Comment not found.', [], Response::HTTP_NOT_FOUND, false);
        }

        $comment->update(['text' => $request->text]);

        return $this->customResponse('Successfully updated!', new CommentResource($comment));
    }

    /**
     * Remove the specified Comment
     *
     * @param  App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        /**
         * Return 404 Page Not Found if the Comment's user_id
         * is not equal to logged in user's id.
         */
        if ($comment->user_id != Auth::id()) {
            return $this->customResponse('Comment not found.', [], Response::HTTP_NOT_FOUND, false);
        }

        $comment->delete();

        return $this->customResponse('Successfully deleted!', [], Response::HTTP_NO_CONTENT);
    }
}
