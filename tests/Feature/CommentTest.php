<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Post;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }

    public function test_user_can_comment()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $this->post(route('comments.store'), [
            'post_id' => $post->id,
            'text' => 'Sample comment text!'
        ])->assertOk();

        $this->assertDatabaseHas('comments', ['text' => 'Sample comment text!']);
    }
}
