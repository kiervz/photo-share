<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Post;

class VoteTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }

    public function test_user_can_upvote()
    {
        $post = Post::factory()->create();

        $this->post(route('votes.up', $post->id))
            ->assertOk();
    }

    public function test_user_can_downvote()
    {
        $post = Post::factory()->create();

        $this->post(route('votes.down', $post->id))
            ->assertOk();
    }
}
