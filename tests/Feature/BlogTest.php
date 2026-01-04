<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows only published posts on index', function () {
    Post::factory()->count(1)->create(['title' => 'Published Post', 'status' => 'published', 'published_at' => now()->subDay()]);
    Post::factory()->count(1)->create(['title' => 'Draft Post', 'status' => 'draft']);

    $response = $this->get(route('blog.index'));

    $response->assertStatus(200);
    $response->assertSee('Published Post');
    $response->assertDontSee('Draft Post');
});

it('can search for posts', function () {
    Post::factory()->create(['title' => 'Laravel Guide', 'status' => 'published', 'published_at' => now()]);
    Post::factory()->create(['title' => 'Vite Tutorial', 'status' => 'published', 'published_at' => now()]);

    $response = $this->get(route('blog.index', ['search' => 'Laravel']));

    $response->assertSee('Laravel Guide');
    $response->assertDontSee('Vite Tutorial');
});

it('can filter by category', function () {
    $category = Category::factory()->create(['name' => 'PHP']);
    $post = Post::factory()->create([
        'title' => 'PHP Post',
        'status' => 'published',
        'published_at' => now(),
        'category_id' => $category->id,
    ]);

    Post::factory()->create(['title' => 'Other Post', 'status' => 'published', 'published_at' => now()]);

    $response = $this->get(route('blog.index', ['category' => $category->slug]));

    $response->assertSee('PHP Post');
    $response->assertDontSee('Other Post');
});

it('can filter by tag', function () {
    $tag = Tag::factory()->create(['name' => 'Backend']);
    $post = Post::factory()->create(['title' => 'Backend Post', 'status' => 'published', 'published_at' => now()]);
    $post->tags()->attach($tag);

    Post::factory()->create(['title' => 'Frontend Post', 'status' => 'published', 'published_at' => now()]);

    $response = $this->get(route('blog.index', ['tag' => $tag->slug]));

    $response->assertSee('Backend Post');
    $response->assertDontSee('Frontend Post');
});

it('shows a single post', function () {
    $post = Post::factory()->create(['status' => 'published', 'published_at' => now()]);

    $response = $this->get(route('blog.show', $post));

    $response->assertStatus(200);
    $response->assertSee($post->title);
});

it('aborts 404 for draft posts on show route', function () {
    $post = Post::factory()->create(['status' => 'draft']);

    $response = $this->get(route('blog.show', $post));

    $response->assertNotFound();
});

it('allows admin to preview draft posts', function () {
    $admin = User::factory()->create();
    $post = Post::factory()->create(['status' => 'draft']);

    $response = $this->actingAs($admin)->get(route('blog.preview', $post));

    $response->assertStatus(200);
    $response->assertSee($post->title);
    $response->assertSee('PREVIEW MODE');
});

it('prevents guests from previewing posts', function () {
    $post = Post::factory()->create(['status' => 'draft']);

    $response = $this->get(route('blog.preview', $post));

    $response->assertRedirect('/admin/login'); // Filament's default login or Laravel default
});

it('caches the post listing', function () {
    Cache::shouldReceive('remember')
        ->once()
        ->andReturn(Post::factory()->count(1)->create(['status' => 'published', 'published_at' => now()]));

    $this->get(route('blog.index'));
});
