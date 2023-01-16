<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Vote;

use Auth;

class VoteController extends Controller
{
    /**
     * Upvote the specified Post
     *
     * @param  App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function upVote(Post $post)
    {
        $status = Post::UNVOTE;
        $vote = Vote::where([
            'post_id' => $post->id,
            'user_id' => Auth::id()
        ])->first();

        /** If vote already exist. */
        if ($vote) {
            /** Check if vote is upvote (1) then delete. */
            if ($vote->vote === Post::UPVOTE) $vote->delete();

            /** check if vote is downvote (-1) then update to upvote (1). */
            if ($vote->vote === Post::DOWNVOTE) {
                $vote->update(['vote' => Post::UPVOTE]);
                $status = Post::UPVOTE;
            }
        } else {
            /** Create new upvote entry. */
            Vote::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'vote' => Post::UPVOTE
            ]);

            $status = Post::UPVOTE;
        }

        /** Update post's total votes. */
        $post->update(['total_votes' => $post->votes->sum('vote')]);

        return $this->customResponse('success', [
            'total_votes' => $post->total_votes,
            'status' => $status
        ]);
    }

    /**
     * Downvote the specified Post
     *
     * @param  App\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function downVote(Post $post)
    {
        $status = Post::UNVOTE;
        $vote = Vote::where([
            'post_id' => $post->id,
            'user_id' => Auth::id()
        ])->first();

        /** If vote already exist. */
        if ($vote) {
            /** Check if vote is downvote (-1) then delete. */
            if ($vote->vote === Post::DOWNVOTE) $vote->delete();

            /** Check if vote is upvote (1) then update to downvote (-1) */
            if ($vote->vote === Post::UPVOTE) {
                $vote->update(['vote' => Post::DOWNVOTE]);
                $status = Post::DOWNVOTE;
            }
        } else {
            /** Create new upvote entry. */
            Vote::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'vote' => Post::DOWNVOTE
            ]);

            $status = Post::DOWNVOTE;
        }

        /** Update post's total votes. */
        $post->update(['total_votes' => $post->votes->sum('vote')]);

        return $this->customResponse('success', [
            'total_votes' => $post->total_votes,
            'status' => $status
        ]);
    }
}
