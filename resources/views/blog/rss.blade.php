@php echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL; @endphp
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ config('app.name') }} Blog</title>
        <link>{{ url('/') }}</link>
        <description>Statum Blog - Engineering and Fintech Insights</description>
        <language>en-us</language>
        <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
        <atom:link href="{{ url('/rss.xml') }}" rel="self" type="application/rss+xml" />

        @foreach($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ route('blog.show', $post) }}</link>
                <description><![CDATA[{!! $post->excerpt !!}]]></description>
                <author>{{ $post->author?->name ?? 'Statum Team' }}</author>
                <guid>{{ route('blog.show', $post) }}</guid>
                <pubDate>{{ $post->published_at->toRssString() }}</pubDate>
                @if($post->category)
                    <category>{{ $post->category->name }}</category>
                @endif
            </item>
        @endforeach
    </channel>
</rss>
