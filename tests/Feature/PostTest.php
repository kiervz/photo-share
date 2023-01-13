<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Post;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->authUser();
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
}
