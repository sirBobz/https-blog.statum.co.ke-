<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    public function index(Request $request, ?Category $category = null, ?Tag $tag = null)
    {
        $search = $request->input('search');

        // Use route parameters if present, fallback to query parameters for backward compatibility or search filters
        $categorySlug = $category?->slug ?? $request->input('category');
        $tagSlug = $tag?->slug ?? $request->input('tag');

        // We only cache the base query results.
        // Filtered results might be too varied for simple caching without a more complex strategy.
        // But for this requirement, we'll cache the paginated results for the current page and filters.
        $cacheKey = 'posts_' . md5(json_encode(array_merge($request->all(), [
            'category_slug' => $categorySlug,
            'tag_slug' => $tagSlug,
        ])));

        $posts = Cache::remember($cacheKey, 3600, function () use ($search, $categorySlug, $tagSlug) {
            return Post::query()
                ->published()
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('excerpt', 'like', "%{$search}%")
                            ->orWhere('body', 'like', "%{$search}%");
                    });
                })
                ->when($categorySlug, function ($query, $categorySlug) {
                    $query->whereHas('category', function ($q) use ($categorySlug) {
                        $q->where('slug', $categorySlug);
                    });
                })
                ->when($tagSlug, function ($query, $tagSlug) {
                    $query->whereHas('tags', function ($q) use ($tagSlug) {
                        $q->where('slug', $tagSlug);
                    });
                })
                ->with(['category', 'tags', 'author'])
                ->latest('published_at')
                ->paginate(12);
        });

        $categories = Category::all();
        $tags = Tag::all();

        return view('blog.index', compact('posts', 'categories', 'tags'));
    }

    public function show(Post $post)
    {
        // Explicitly check if the post should be visible
        if ($post->status !== 'published') {
            abort(404);
        }

        if ($post->published_at && $post->published_at->isFuture()) {
            abort(404);
        }

        $post->load(['category', 'tags', 'author']);

        $relatedPosts = $this->getRelatedPosts($post);

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    public function preview(Post $post)
    {
        // This is only accessible via Filament's preview link which we'll protect or just use auth
        if (!auth()->check()) {
            abort(403);
        }

        $post->load(['category', 'tags', 'author']);
        $relatedPosts = $this->getRelatedPosts($post);

        return view('blog.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'isPreview' => true,
        ]);
    }

    protected function getRelatedPosts(Post $post)
    {
        // 1. Same category first
        // 2. Shared tags second

        $categoryPosts = Post::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->take(3)
            ->get();

        if ($categoryPosts->count() < 3) {
            $tagIds = $post->tags->pluck('id');
            $remainingCount = 3 - $categoryPosts->count();

            $tagPosts = Post::query()
                ->published()
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $categoryPosts->pluck('id'))
                ->whereHas('tags', function ($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds);
                })
                ->take($remainingCount)
                ->get();

            return $categoryPosts->concat($tagPosts);
        }

        return $categoryPosts;
    }

    public function rss()
    {
        $posts = Cache::remember('rss_feed', 3600, function () {
            return Post::published()
                ->with(['author', 'category'])
                ->latest('published_at')
                ->take(20)
                ->get();
        });

        return response()->view('blog.rss', compact('posts'), 200)
            ->header('Content-Type', 'text/xml');
    }
}
