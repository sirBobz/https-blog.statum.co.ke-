@extends('layouts.blog')

@section('title', ($post->meta_title ?? $post->title) . ' | ' . config('app.name'))
@section('meta_description', $post->meta_description ?? $post->excerpt)
@section('og_type', 'article')
@section('og_image', $post->og_image ? asset('storage/' . $post->og_image) : ($post->featured_image ? asset('storage/' . $post->featured_image) : asset('default-og-image.jpg')))

@section('header_scripts')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BlogPosting",
        "headline": "{{ addslashes($post->title) }}",
        "description": "{{ addslashes($post->meta_description ?? $post->excerpt) }}",
        "image": "{{ $post->featured_image ? asset('storage/' . $post->featured_image) : asset('default-og-image.jpg') }}",
        "author": {
            "@@type": "Person",
            "name": "{{ addslashes($post->author?->name ?? 'Statum Team') }}"
        },
        "publisher": {
            "@@type": "Organization",
            "name": "{{ config('app.name') }}",
            "logo": {
                "@@type": "ImageObject",
                "url": "{{ asset('logo.png') }}"
            }
        },
        "datePublished": "{{ $post->published_at?->toIso8601String() }}",
        "dateModified": "{{ $post->updated_at?->toIso8601String() }}"
    }
    </script>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        @if($post->status !== 'published')
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-8" role="alert">
                <p class="font-bold">PREVIEW MODE</p>
                <p>This post is currently {{ $post->status }}. It is only visible to administrators.</p>
            </div>
        @endif
        <!-- Breadcrumbs & Category -->
        <div class="flex items-center gap-3 mb-8 text-xs font-bold uppercase tracking-[0.2em]">
            <a href="{{ route('blog.index') }}" class="text-blue-600 hover:text-blue-700 transition-colors">Blog</a>
            <span class="text-gray-300">/</span>
            @if($post->category)
                <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" 
                   class="text-gray-600 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                    {{ $post->category->name }}
                </a>
            @endif
        </div>

        <!-- Header Section -->
        <header class="mb-12 space-y-8 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 dark:text-white leading-[1.1] tracking-tight">
                {{ $post->title }}
            </h1>

            <div class="flex flex-col items-center justify-center gap-6">
                <!-- Author & Meta -->
                <div class="flex items-center gap-4 py-4 px-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    <img src="{{ $post->author?->avatar ? asset('storage/' . $post->author->avatar) : 'https://ui-avatars.com/api/?name=' . ($post->author?->name ?? 'A') }}" 
                         alt="{{ $post->author?->name }}" 
                         class="w-12 h-12 rounded-full object-cover">
                    <div class="text-left">
                        <div class="font-bold text-gray-900 dark:text-white">{{ $post->author?->name }}</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-widest">
                            {{ $post->published_at?->format('M d, Y') }} • {{ $post->reading_time }} min read
                        </div>
                    </div>
                </div>

                <!-- Share Actions -->
                <div class="flex items-center gap-4" x-data="{ copied: false, shareUrl: '{{ url()->current() }}' }">
                    <button @@click="navigator.clipboard.writeText(shareUrl); copied = true; setTimeout(() => copied = false, 2000)"
                            aria-label="Copy link"
                            class="p-3 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-blue-600 hover:text-white transition-all shadow-sm group">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!copied">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m-5 10l5-5m0 0l5 5m-5-5v12"></path>
                        </svg>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="copied" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}" target="_blank"
                       aria-label="Share on Twitter"
                       class="p-3 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-[#1DA1F2] hover:text-white transition-all shadow-sm">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path>
                        </svg>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank"
                       aria-label="Share on LinkedIn"
                       class="p-3 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-[#0077b5] hover:text-white transition-all shadow-sm">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                        </svg>
                    </a>
                </div>
            </div>

            @if($post->featured_image)
                <div class="mt-12 rounded-[2.5rem] overflow-hidden shadow-2xl border-8 border-white dark:border-gray-900 bg-gray-200 dark:bg-gray-800 aspect-[16/9]">
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                        class="w-full h-full object-cover">
                </div>
            @endif
        </header>

        <!-- Main Post Body -->
        <article class="prose prose-lg dark:prose-invert max-w-none pt-8
                        prose-headings:font-extrabold prose-headings:tracking-tight prose-headings:text-gray-900 dark:prose-headings:text-white
                        prose-p:leading-relaxed prose-p:text-gray-700 dark:prose-p:text-gray-300
                        prose-a:text-blue-600 dark:prose-a:text-blue-400 prose-a:no-underline hover:prose-a:underline
                        prose-code:text-blue-600 dark:prose-code:text-blue-400 prose-code:bg-blue-50 dark:prose-code:bg-blue-900/30 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:before:content-none prose-code:after:content-none
                        prose-blockquote:border-l-4 prose-blockquote:border-blue-600 prose-blockquote:bg-blue-50 dark:prose-blockquote:bg-blue-900/10 prose-blockquote:py-1 prose-blockquote:px-6 prose-blockquote:italic prose-blockquote:font-medium
                        prose-img:rounded-3xl prose-img:shadow-xl">
            {!! Str::markdown($post->body) !!}
        </article>

        <!-- Post Footer: Author Bio -->
        <div class="mt-20 p-8 rounded-3xl bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 flex flex-col md:flex-row gap-8 items-start">
            <img src="{{ $post->author?->avatar ? asset('storage/' . $post->author->avatar) : 'https://ui-avatars.com/api/?name=' . ($post->author?->name ?? 'A') }}" 
                 alt="{{ $post->author?->name }}" 
                 class="w-20 h-20 rounded-2xl object-cover shadow-lg">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ $post->author?->name }}</h4>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Engineering at Statum</div>
                    </div>
                </div>
                <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                    {{ $post->author?->bio ?? 'Writers and engineers at Statum sharing insights on fintech, infrastructure and security.' }}
                </p>
                <div class="flex gap-4">
                    @if($post->author?->twitter)
                        <a href="https://twitter.com/{{ ltrim($post->author->twitter, '@') }}" aria-label="Follow on Twitter" class="text-blue-500 hover:text-blue-600 transition-colors">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
                        </a>
                    @endif
                    @if($post->author?->linkedin)
                        <a href="{{ $post->author->linkedin }}" aria-label="Connect on LinkedIn" class="text-[#0077b5] hover:text-[#0077b5]/80 transition-colors">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M22.239 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.017zM7.12 20.452H3.558V9h3.562v11.452zM5.339 7.433c-1.146 0-2.066-.926-2.066-2.065 0-1.139.92-2.063 2.066-2.063 1.14 0 2.064.924 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm15.113 13.019h-3.558v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286z"></path></svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Posts Section -->
        @if($relatedPosts->count() > 0)
            <div class="mt-32 space-y-12">
                <div class="flex items-center justify-between">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">More from the blog</h2>
                    <a href="{{ route('blog.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest inline-flex items-center group">
                        View all
                        <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedPosts as $related)
                        <article class="group space-y-4">
                            <a href="{{ route('blog.show', $related) }}" class="block aspect-[16/10] rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800 border border-gray-100 dark:border-gray-800 shadow-sm">
                                <img src="{{ $related->featured_image ? asset('storage/' . $related->featured_image) : 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=400&q=80' }}"
                                    alt="{{ $related->title }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            </a>
                            <div class="space-y-2">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors leading-snug">
                                    <a href="{{ route('blog.show', $related) }}">{{ $related->title }}</a>
                                </h3>
                                <div class="text-[10px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-widest">
                                    {{ $related->published_at?->format('M d, Y') }} • {{ $related->reading_time }} min read
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection