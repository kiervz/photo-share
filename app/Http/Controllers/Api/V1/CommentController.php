<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Comment\StoreRequest;

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

        Comment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'text' => $request->text
        ]);

        return $this->customResponse('Successfully created!');
    }
}
