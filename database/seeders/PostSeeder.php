<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::factory(5)->create();
        $tags = Tag::factory(10)->create();

        Post::factory(20)->create()->each(function (Post $post) use ($categories, $tags) {
            $post->categories()->attach($categories->random(rand(1, 2)));
            $post->tags()->attach($tags->random(rand(2, 4)));
        });
    }
}
