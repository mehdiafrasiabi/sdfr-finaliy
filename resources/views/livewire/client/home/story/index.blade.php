<div>
    @php

        $userId = auth()->id();

        $formattedStories = $stories->map(function($item) use ($userId) {

            $isLiked = $userId ? $item->likes->contains('user_id', $userId) : false;

            return [

                'id' => $item->id,

                'type' => $item->type,

                'user' => $item->title,

                'avatar' => "/stories/thumbnail/{$item->thumbnail}",

                'url' => $item->type === 'video' ? $item->story : "/stories/story/{$item->story}",

                'duration' => $item->type === 'image' ? 5000 : null,

                'link' => $item->widget_link,

                'linkTitle' => $item->widget_title,

                'likesCount' => $item->likes_count,

                'isLiked' => $isLiked,

            ];

        });

    @endphp

    @if($stories->count() > 0)

        <section>

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
                const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
                const loginUrl = '{{ route("client.auth.login") }}';

                const storyPlayer = new StoryPlayer('stories-container', stories, {
                    isLoggedIn,
                    loginUrl,
                    onLike: function (storyId) {
                    @this.call('toggleLike', storyId)
                        ;
                    }
                });
            </script>

        @endpush

    @endif

</div>
