<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\BlogPost;
use App\Models\BlogComment;

class BlogCommentTest extends TestCase
{
    use RefreshDatabase;

// a BlogComment belongs to a Post.
    public function testBlogCommentBelongsToPost()
    {
        // Create a Post
        $post = BlogPost::factory()->create();

        // Create a BlogComment for the Post
        $comment = BlogComment::factory()->create(['id' => $post->id]);

        // Assert that the BlogComment belongs to the Post
        $this->assertInstanceOf(BlogPost::class, $comment->post);
        $this->assertEquals($post->id, $comment->post()->id);
    }

    //a Post has many BlogComments
    public function testPostHasManyBlogComment()
    {
        // Create a Post
        $post = BlogPost::factory()->create();

        // Create multiple BlogComment for the Post
        $comments = BlogComment::factory(5)->create(['id' => $post->id]);

        // Assert that Post has many BlogComment
        $this->assertCount(5, $post->comments());
        $this->assertInstanceOf(BlogComment::class, $post->comments([0]));
    }

}
