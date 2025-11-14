@php
    $formattedStories = $stories->map(function($item) {
        return [
            'type' => 'video',
            'user' => $item->title,
            'avatar' => "/stories/thumbnail/{$item->thumbnail}",
            'url' => "/stories/story/{$item->story}",
            'duration' => null,
            'link' => 'https://sdfr.me',
        ];
    });
@endphp

<div>
    <section class="py-4">
        <h2 class="sr-only">استوری های SDFR</h2>
        <div class="container">
            <div id="stories-container" role="region" aria-labelledby="stories-title">
                <h3 id="stories-title" class="sr-only">استوری های SDFR</h3>
            </div>
        </div>
    </section>

    @push('script')
        <script>
            const stories = @json($formattedStories);
            console.log("Generated stories:", stories); // تست خروجی

            new StoryPlayer('stories-container', stories);
        </script>
    @endpush
</div>
