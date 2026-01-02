@extends('layouts.blog')

@section('title', 'Blog | ' . config('app.name'))

@section('content')
    <div class="space-y-12">
        <!-- Header -->
        <div class="text-center space-y-4">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">Latest Stories</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Explore our latest thoughts, guides, and
                insights curated just for you.</p>
        </div>

        <!-- Filters & Search -->
        <div
            class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 flex flex-col md:flex-row gap-6 items-center justify-between">
            <div class="flex flex-wrap gap-2 items-center">
                <a href="{{ route('blog.index') }}"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ !request('category') && !request('tag') ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    All
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ request('category') == $category->slug ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <form action="{{ route('blog.index') }}" method="GET" class="relative w-full md:w-72">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..."
                    class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 focus:ring-2 focus:ring-blue-500 transition-all">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </form>
        </div>

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
                <article
                    class="group bg-white dark:bg-gray-900 rounded-2xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-800 hover:shadow-xl transition-all duration-300">
                    <a href="{{ $post->slug ? route('blog.show', $post) : '#' }}"
                        class="block relative aspect-video overflow-hidden">

                        <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800&auto=format&fit=crop&q=60' }}"
                            alt="{{ $post->title }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @if($post->status === 'draft')
                            <span
                                class="absolute top-4 left-4 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded">DRAFT</span>
                        @endif
                    </a>

                    <div class="p-6 space-y-4">
                        <div class="flex gap-2">
                            @foreach($post->categories->take(2) as $category)
                                <span
                                    class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">{{ $category->name }}</span>
                            @endforeach
                        </div>

                        <h2 class="text-xl font-bold group-hover:text-blue-600 transition-colors">
                            <a href="{{ $post->slug ? route('blog.show', $post) : '#' }}">{{ $post->title }}</a>
                        </h2>

                        <p class="text-gray-600 dark:text-gray-400 line-clamp-3 text-sm leading-relaxed">
                            {{ $post->excerpt }}
                        </p>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-800">
                            <span
                                class="text-xs text-gray-500">{{ $post->published_at?->format('M d, Y') ?? 'Not published' }}</span>
                            <a href="{{ $post->slug ? route('blog.show', $post) : '#' }}"
                                class="text-blue-600 dark:text-blue-400 text-sm font-bold inline-flex items-center group/btn">
                                Read More
                                <svg class="w-4 h-4 ml-1 transition-transform group-hover/btn:translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-20 grayscale opacity-50">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    <p class="text-xl font-medium">No posts found.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    </div>
@endsection