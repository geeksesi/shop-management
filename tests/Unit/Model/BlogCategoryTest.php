<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\BlogPost;
use App\Models\BlogCategory;

class BlogCategoryTest extends TestCase
{
//getting posts for a blog category
    public function testPostsRelationship()
    {
        // Create a blog category
        $category = BlogCategory::factory()->create();

        // Create some posts for the blog category
        $post1 = BlogPost::factory()->create(['id' => $category->id]);
        $post2 = BlogPost::factory()->create(['id' => $category->id]);

        // Get the posts for the category
        $posts = $category->posts;

        // Assert that the correct number of posts are returned
        $this->assertCount(2, $posts);

        // Assert that the posts are in the correct order (newest first)
        $this->assertEquals($post2->id, $posts->first()->id);
        $this->assertEquals($post1->id, $posts->last()->id);
    }
//Test creating a blog category
    public function testCreateBlogCategory()
    {
        // Create a new blog category
        $category = BlogCategory::factory()->create([
            'title' => 'Test Category',
            'description' => 'This is a test category',
        ]);

        // Assert that the category was created successfully
        $this->assertEquals('Test Category', $category->title);
        $this->assertEquals('This is a test category', $category->description);
    }

}
