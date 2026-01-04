@extends('layouts.blog')

@section('title', 'Engineering Blog | ' . config('app.name'))

@section('content')
    @php
        $jsPosts = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'category_slug' => $post->category?->slug,
                'search_text' => strtolower(
                    $post->title . ' ' . 
                    ($post->excerpt ?? '') . ' ' . 
                    $post->tags->pluck('name')->implode(' ')
                )
            ];
        })->values();
    @endphp

    <div x-data="{ 
            search: '',
            activeCategory: '{{ $categorySlug ?? request('category', 'all') }}',
            posts: {{ Js::from($jsPosts) }},
            get visiblePostIds() {
                if (!this.search && this.activeCategory === 'all') {
                    return this.posts.map(p => p.id);
                }
                const term = this.search.toLowerCase();
                return this.posts
                    .filter(post => {
                        const matchesCategory = this.activeCategory === 'all' || post.category_slug === this.activeCategory;
                        const matchesSearch = !term || post.search_text.includes(term);
                        return matchesCategory && matchesSearch;
                    })
                    .map(p => p.id);
            }
        }" class="space-y-10">

        <!-- Filters & Search Toolbar -->
        <div
            class="sticky top-[64px] z-40 bg-gray-50/80 dark:bg-gray-950/80 backdrop-blur-md py-2 border-b border-gray-200 dark:border-gray-800">
            <div class="flex flex-col md:flex-row gap-6 items-center justify-between">
                <!-- Category Pills -->
                <div class="flex flex-wrap gap-2 items-center justify-center md:justify-start">
                    <a href="{{ route('blog.index') }}"
                        :class="activeCategory === 'all' ? 'bg-gray-950 text-white dark:bg-white dark:text-gray-950' : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'"
                        class="px-4 py-1.5 rounded-full text-xs font-semibold transition-all shadow-sm border border-gray-200 dark:border-gray-800">
                        All articles
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('blog.category', ['category' => $category->slug]) }}"
                            :class="activeCategory === '{{ $category->slug }}' ? 'bg-gray-950 text-white dark:bg-white dark:text-gray-950' : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'"
                            class="px-4 py-1.5 rounded-full text-xs font-semibold transition-all shadow-sm border border-gray-200 dark:border-gray-800">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Search Input -->
                <div class="relative w-full md:w-72">
                    <input type="text" x-model.debounce.300ms="search" placeholder="Search articles..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm focus:ring-2 focus:ring-blue-500 transition-all text-sm">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 min-h-[50vh]">
            @foreach($posts as $index => $post)
                <article
                    x-show="visiblePostIds.includes({{ $post->id }})"
                    x-transition.opacity.duration.300ms
                    class="group flex flex-col bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 hover:shadow-xl dark:hover:shadow-2xl transition-all duration-300">
                    <!-- Image Container -->
                    <a href="{{ route('blog.show', $post) }}" class="relative block aspect-[16/10] overflow-hidden">
                        <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&q=80' }}"
                            alt="{{ $post->title }}"
                            @if($index === 0) fetchpriority="high" loading="eager" @else loading="lazy" @endif
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-950/20 to-transparent"></div>

                        <!-- Category Badge -->
                        @if($post->category)
                            <div class="absolute top-3 left-3">
                                <span
                                    style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }}; border-color: {{ $post->category->color }}40;"
                                    class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest border backdrop-blur-sm">
                                    {{ $post->category->name }}
                                </span>
                            </div>
                        @endif
                    </a>

                    <!-- Content -->
                    <div class="p-5 flex-grow flex flex-col">
                        <h2
                            class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors leading-snug mb-2">
                            <a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a>
                        </h2>

                        <p class="text-gray-700 dark:text-gray-300 text-sm line-clamp-2 leading-relaxed mb-4">
                            {{ $post->excerpt }}
                        </p>

                        <div class="mt-auto flex items-center justify-between">
                            <!-- Author -->
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $post->author?->avatar ? asset('storage/' . $post->author->avatar) : 'https://ui-avatars.com/api/?name=' . ($post->author?->name ?? 'A') }}"
                                    class="w-6 h-6 rounded-full object-cover border border-white dark:border-gray-800 shadow-sm"
                                    alt="{{ $post->author?->name }}">
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                    {{ $post->author?->name }}
                                </span>
                            </div>

                            <!-- Meta -->
                            <div
                                class="flex flex-col items-end text-[10px] text-gray-600 dark:text-gray-400 font-medium uppercase tracking-wide">
                                <span>
                                    {{ $post->published_at?->format('M d, Y') }}
                                </span>
                                <span>{{ $post->reading_time }} min read</span>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Empty State -->
        <div x-show="visiblePostIds.length === 0" x-cloak
            class="text-center py-16 bg-white dark:bg-gray-900 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 dark:bg-gray-800 mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <p class="text-base font-medium text-gray-900 dark:text-white">No matches for "<span x-text="search"></span>"
            </p>
            <button @click="search = ''" class="mt-2 text-blue-600 text-sm font-semibold hover:underline">Clear
                search</button>
        </div>

        <!-- Pagination -->
        <div class="mt-8" x-show="search === ''">
            {{ $posts->appends(request()->query())->links() }}
        </div>

        <!-- Tags Cloud (Optional) -->
        <div class="pt-16 border-t border-gray-200 dark:border-gray-800">
            <h3 class="text-center text-xs font-bold uppercase tracking-[0.2em] text-gray-600 dark:text-gray-400 mb-6">
                Popular Topics</h3>
            <div class="flex flex-wrap justify-center gap-2 max-w-3xl mx-auto">
                @foreach($tags as $tag)
                    <a href="{{ route('blog.tag', ['tag' => $tag->slug]) }}"
                        class="px-3 py-1 rounded-md bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-xs text-gray-700 dark:text-gray-300 hover:border-blue-500 dark:hover:border-blue-500 hover:text-blue-500 dark:hover:text-blue-500 transition-all">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection