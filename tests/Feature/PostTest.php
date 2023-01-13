<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Post;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }

    public function test_user_can_fetch_all_posts()
    {
        Post::factory(10)->create();

        $this->get(route('posts.index'))->assertSuccessful();

        $this->assertDatabaseCount('posts', 10);
    }

    public function test_user_can_fetch_specific_post()
    {
        $post = Post::factory()->create();

        $this->get(route('posts.show', $post))->assertSuccessful();
    }

    public function test_user_can_update_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $this->put(route('posts.update', $post), [
            'description' => 'updated description'
        ])->assertOk();

        $this->assertDatabaseHas('posts', ['description' => 'updated description']);
    }
}
