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
        $status = Post::UNVOTE;
        $vote = Vote::where([
            'post_id' => $post->id,
            'user_id' => Auth::id()
        ])->first();

        /** if vote already exist */
        if ($vote) {
            /** check if vote is upvote (1) then delete */
            if ($vote->vote === 1) $vote->delete();

            /** check if vote is downvote (-1) then update to upvote (1) */
            if ($vote->vote === -1) {
                $vote->update(['vote' => 1]);
                $status = Post::UPVOTE;
            }
        } else {
            /** create new upvote entry */
            Vote::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'vote' => 1
            ]);

            $status = Post::UPVOTE;
        }

        /** update post's total votes */
        $post->update(['total_votes' => $post->votes->sum('vote')]);

        return $this->customResponse('success', [
            'total_votes' => $post->total_votes,
            'status' => $status
        ]);
    }

    public function downVote(Post $post)
    {
        $status = Post::UNVOTE;
        $vote = Vote::where([
            'post_id' => $post->id,
            'user_id' => Auth::id()
        ])->first();

        /** if vote already exist */
        if ($vote) {
            /** check if vote is downvote (-1) then delete */
            if ($vote->vote === -1) $vote->delete();

            /** check if vote is upvote (1) then update to downvote (-1) */
            if ($vote->vote === 1) {
                $vote->update(['vote' => -1]);
                $status = Post::DOWNVOTE;
            }
        } else {
            /** create new upvote entry */
            Vote::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'vote' => -1
            ]);

            $status = Post::DOWNVOTE;
        }

        /** update post's total votes */
        $post->update(['total_votes' => $post->votes->sum('vote')]);

        return $this->customResponse('success', [
            'total_votes' => $post->total_votes,
            'status' => $status
        ]);
    }
}
