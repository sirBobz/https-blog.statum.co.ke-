<?php

use Spatie\LaravelMarkdown\MarkdownRenderer;

it('can render markdown', function () {
    /** @var MarkdownRenderer $renderer */
    $renderer = app(MarkdownRenderer::class);
    $markdown = '# Hello World';
    $html = $renderer->toHtml($markdown);

    expect($html)->toContain('<h1 id="hello-world">Hello World</h1>');
});

it('renders commonmark standard correctly', function () {
    /** @var MarkdownRenderer $renderer */
    $renderer = app(MarkdownRenderer::class);
    // In CommonMark, 'foo_bar_' renders as 'foo_bar_' (no emphasis because of the underscore in the middle of the word)
    // whereas in original Markdown it might be emphasized.
    // However, the most important thing is that it renders.
    $markdown = 'foo_bar_';
    $html = $renderer->toHtml($markdown);

    expect($html)->toContain('foo_bar_');
});
