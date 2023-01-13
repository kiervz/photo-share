<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Post;
use App\Models\Comment;

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

    public function test_user_can_update_comment()
    {
        $post = Post::factory()->create();

        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $this->user->id
        ]);

        $this->put(route('comments.update', $comment->id), [
            'text' => 'updated comment!'
        ])->assertOk();

        $this->assertDatabaseHas('comments', ['text' => 'updated comment!']);
    }

    public function test_user_can_delete_comment()
    {
        $post = Post::factory()->create();

        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $this->user->id
        ]);

        $this->delete(route('comments.destroy', $comment->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
