<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Vote;

use Auth;

class VoteController extends Controller
{
    public function upVote(Post $post)
    {
        $vote = Vote::where([
            'post_id' => $post->id,
            'user_id' => Auth::id()
        ])->first();

        /** if vote already exist */
        if ($vote) {
            /** check if vote is upvote (1) then delete */
            if ($vote->vote === 1) $vote->delete();

            /** check if vote is downvote (-1) then update to upvote (1) */
            if ($vote->vote === -1) $vote->update(['vote' => 1]);
        } else {
            /** create new upvote entry */
            Vote::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'vote' => 1
            ]);
        }

        return $this->customResponse('success');
    }

    public function downVote(Post $post)
    {
        $vote = Vote::where([
            'post_id' => $post->id,
            'user_id' => Auth::id()
        ])->first();

        /** if vote already exist */
        if ($vote) {
            /** check if vote is downvote (-1) then delete */
            if ($vote->vote === -1) $vote->delete();

            /** check if vote is upvote (1) then update to downvote (-1) */
            if ($vote->vote === 1) $vote->update(['vote' => -1]);
        } else {
            /** create new upvote entry */
            Vote::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'vote' => -1
            ]);
        }

        return $this->customResponse('success');
    }
}
