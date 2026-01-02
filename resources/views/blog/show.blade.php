@extends('layouts.blog')

@section('title', ($post->title ?? 'Post') . ' | ' . config('app.name'))
@section('meta_description', $post->meta_description ?? $post->excerpt)
@section('og_type', 'article')
@if($post->featured_image)
@section('og_image', asset('storage/' . $post->featured_image))
@endif

@section('header_scripts')
    <script type="application/ld+json">
            {
              "@@context": "https://schema.org",
              "@@type": "BlogPosting",
              "headline": "{{ $post->title }}",
              "description": "{{ $post->meta_description ?? $post->excerpt }}",
              "image": "{{ $post->featured_image ? asset('storage/' . $post->featured_image) : asset('default-og-image.jpg') }}",
              "author": {
                "@@type": "Person",
                "name": "Admin"
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
    <div class="max-w-4xl mx-auto space-y-12">
        <!-- Breadcrumbs -->
        <nav class="flex text-sm text-gray-500 dark:text-gray-400 gap-2">
            <a href="{{ route('blog.index') }}" class="hover:text-blue-600 transition-colors">Blog</a>
            <span>/</span>
            <span class="text-gray-900 dark:text-gray-100 truncate">{{ $post->title }}</span>
        </nav>

        <!-- Header -->
        <header class="space-y-6">
            <div class="space-y-4">
                <div class="flex flex-wrap gap-2">
                    @foreach($post->categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                            class="bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider hover:bg-blue-200 transition-colors">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">{{ $post->title }}</h1>
                <div class="flex items-center gap-4 text-gray-500 dark:text-gray-400 text-sm">
                    <span>By Admin</span>
                    <span>•</span>
                    <span>{{ $post->published_at?->format('M d, Y') ?? 'Not published' }}</span>
                    @if(isset($isPreview))
                        <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded ml-2">PREVIEW
                            MODE</span>
                    @endif
                </div>
            </div>

            @if($post->featured_image)
                <div class="rounded-3xl overflow-hidden shadow-2xl aspect-[21/9]">
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                        class="w-full h-full object-cover">
                </div>
            @endif
        </header>

        <!-- Content -->
        <article
            class="prose prose-lg dark:prose-invert max-w-none prose-headings:font-bold prose-a:text-blue-600 hover:prose-a:text-blue-500 prose-img:rounded-2xl prose-img:shadow-lg">
            {!! $post->body !!}
        </article>

        <!-- Tags -->
        @if($post->tags->count() > 0)
            <div class="pt-8 border-t border-gray-200 dark:border-gray-800">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}"
                            class="px-3 py-1 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg text-sm transition-colors">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Related Posts -->
        @if($relatedPosts->count() > 0)
            <div class="pt-16 space-y-8">
                <h2 class="text-3xl font-bold">Related Stories</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedPosts as $related)
                        <a href="{{ $related->slug ? route('blog.show', $related) : '#' }}" class="group space-y-4 px-3 py-1">
                            <div class="aspect-video rounded-xl overflow-hidden shadow-sm">
                                <img src="{{ $related->featured_image ? asset('storage/' . $related->featured_image) : 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=400&auto=format&fit=crop&q=60' }}"
                                    alt="{{ $related->title }}"
                                    class="w-full h-full object-cover transition-transform group-hover:scale-110 duration-500">
                            </div>
                            <h4 class="font-bold group-hover:text-blue-600 transition-colors line-clamp-2">{{ $related->title }}
                            </h4>
                            <span class="text-xs text-gray-400">{{ $related->published_at?->format('M d, Y') }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection