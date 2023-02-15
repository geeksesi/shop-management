<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\BlogPost;
use App\Models\BlogComment;


class BlogPostTest extends TestCase
{
    use RefreshDatabase;

    public function testItHasManyComments()
    {
        $post = BlogPost::factory()->create();
        $comment1 = BlogComment::factory()->create(['id' => $post->id]);
        $comment2 = BlogComment::factory()->create(['id' => $post->id]);

        $this->assertInstanceOf(BlogComment::class, $post->comments()->first());
        $this->assertEquals(2, $post->comments()->count());
    }

}
