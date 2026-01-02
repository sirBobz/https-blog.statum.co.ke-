<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categorySlug = $request->input('category');
        $tagSlug = $request->input('tag');

        // We only cache the base query results.
        // Filtered results might be too varied for simple caching without a more complex strategy.
        // But for this requirement, we'll cache the paginated results for the current page and filters.
        $cacheKey = 'posts_'.md5(json_encode($request->all()));

        $posts = Cache::remember($cacheKey, 3600, function () use ($search, $categorySlug, $tagSlug) {
            return Post::query()
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('body', 'like', "%{$search}%");
                    });
                })
                ->when($categorySlug, function ($query, $categorySlug) {
                    $query->whereHas('categories', function ($q) use ($categorySlug) {
                        $q->where('slug', $categorySlug);
                    });
                })
                ->when($tagSlug, function ($query, $tagSlug) {
                    $query->whereHas('tags', function ($q) use ($tagSlug) {
                        $q->where('slug', $tagSlug);
                    });
                })
                ->with(['categories', 'tags'])
                ->latest('published_at')
                ->paginate(10);
        });

        $categories = Category::all();
        $tags = Tag::all();

        return view('blog.index', compact('posts', 'categories', 'tags'));
    }

    public function show(Post $post)
    {
        if ($post->status !== 'published' || ($post->published_at && $post->published_at->isFuture())) {
            abort(404);
        }

        $relatedPosts = Post::query()
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->whereHas('tags', function ($q) use ($post) {
                $q->whereIn('tags.id', $post->tags->pluck('id'));
            })
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    public function preview(Post $post)
    {
        // This is only accessible via Filament's preview link which we'll protect or just use auth
        if (! auth()->check()) {
            abort(403);
        }

        $relatedPosts = Post::query()
            ->where('id', '!=', $post->id)
            ->whereHas('tags', function ($q) use ($post) {
                $q->whereIn('tags.id', $post->tags->pluck('id'));
            })
            ->take(3)
            ->get();

        return view('blog.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'isPreview' => true,
        ]);
    }
}
