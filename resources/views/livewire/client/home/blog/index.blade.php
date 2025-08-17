<div class="max-w-7xl space-y-14 px-4 mx-auto">
    <!-- articles -->
    <div
        class="lg:flex lg:items-center lg:gap-10 bg-gradient-to-l from-secondary to-background rounded-2xl sm:p-10 p-5">
        <div class="lg:w-4/12 flex items-start gap-5 lg:mb-0 mb-8">
                            <span
                                class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-primary text-primary-foreground rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                     class="w-5 h-5">
                                    <path fill-rule="evenodd"
                                          d="M9.664 1.319a.75.75 0 0 1 .672 0 41.059 41.059 0 0 1 8.198 5.424.75.75 0 0 1-.254 1.285 31.372 31.372 0 0 0-7.86 3.83.75.75 0 0 1-.84 0 31.508 31.508 0 0 0-2.08-1.287V9.394c0-.244.116-.463.302-.592a35.504 35.504 0 0 1 3.305-2.033.75.75 0 0 0-.714-1.319 37 37 0 0 0-3.446 2.12A2.216 2.216 0 0 0 6 9.393v.38a31.293 31.293 0 0 0-4.28-1.746.75.75 0 0 1-.254-1.285 41.059 41.059 0 0 1 8.198-5.424ZM6 11.459a29.848 29.848 0 0 0-2.455-1.158 41.029 41.029 0 0 0-.39 3.114.75.75 0 0 0 .419.74c.528.256 1.046.53 1.554.82-.21.324-.455.63-.739.914a.75.75 0 1 0 1.06 1.06c.37-.369.69-.77.96-1.193a26.61 26.61 0 0 1 3.095 2.348.75.75 0 0 0 .992 0 26.547 26.547 0 0 1 5.93-3.95.75.75 0 0 0 .42-.739 41.053 41.053 0 0 0-.39-3.114 29.925 29.925 0 0 0-5.199 2.801 2.25 2.25 0 0 1-2.514 0c-.41-.275-.826-.541-1.25-.797a6.985 6.985 0 0 1-1.084 3.45 26.503 26.503 0 0 0-1.281-.78A5.487 5.487 0 0 0 6 12v-.54Z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </span>
            <div class="flex flex-col space-y-2">
                                <span class="font-black xs:text-2xl text-lg text-primary">
                                   از گوشه و اطراف دنیای کنکور
                                </span>
                <span class="font-semibold xs:text-base text-sm text-muted">
نوشتن کار جالبیه که از هزاران سال همراه ما بوده و کمک کرده تا همیشه به روز باشیم، مادر SDFR فضای رو به شکلی آماده کردیم تا شما بتونید بهترین نتیجه هارو بگیرید :)

                </span>
            </div>
        </div>
        <div class="lg:w-8/12 w-full lg:mx-auto">
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-x-5 gap-y-8 space-y-8">
                @foreach($blogs as $blog)
                <div class="space-y-5">

                        @php
                            $slug = optional($blog->seo)->slug ?? \Illuminate\Support\Str::slug($blog->title);

                                $image = $blog->images->first();
                        @endphp
                    <!-- article:card -->
                    <div class="relative bg-background rounded-xl p-4">
                        <div class="relative mb-3 z-20">
                            <a href="{{ route('client.blog.show', [$blog->blog_code, $slug]) }}" class="block">
                                <img src="{{asset('blogs/'.$blog->id.'/photo/'.$image->path)}}" class="max-w-full rounded-xl" alt=" {{ $blog->seo->meta_title }}">
                            </a>
                            <button type="button"
                                    class="absolute left-3 -bottom-3 w-9 h-9 inline-flex items-center justify-center bg-secondary rounded-full shadow-xl text-muted transition-colors hover:text-red-500 z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                     class="w-5 h-5">
                                    <path
                                        d="m9.653 16.915-.005-.003-.019-.01a20.759 20.759 0 0 1-1.162-.682 22.045 22.045 0 0 1-2.582-1.9C4.045 12.733 2 10.352 2 7.5a4.5 4.5 0 0 1 8-2.828A4.5 4.5 0 0 1 18 7.5c0 2.852-2.044 5.233-3.885 6.82a22.049 22.049 0 0 1-3.744 2.582l-.019.01-.005.003h-.002a.739.739 0 0 1-.69.001l-.002-.001Z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <div class="relative space-y-3 z-10">
                            <h2 class="font-bold text-sm">
                                <a href="{{ route('client.blog.show', [$blog->blog_code, $slug]) }}"
                                   class="line-clamp-1 text-foreground transition-colors hover:text-primary">
                                    {{ $blog->title }}
                                </a>
                            </h2>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1">
                                    <div class="flex-shrink-0 w-8 h-8 border border-white rounded-full overflow-hidden">
                                        <img src="/client/assets/images/blog/01.jpeg"
                                             class="w-full h-full object-cover" alt="...">
                                    </div>
                                    <a href="#"
                                       class="line-clamp-1 font-bold text-xs text-foreground transition-colors hover:text-primary">

                                        گروه آموزشی SDFR</a>
                                </div>
                                <a href="{{ route('client.blog.show', [$blog->blog_code, $slug]) }}"
                                   class="bg-primary/10 rounded-full text-primary transition-all hover:opacity-80 py-1 px-4">
                                    <span class="font-bold text-xxs">{{$blog->category->name}}</span>
                                </a>
                            </div>
                            <div class="flex justify-end">
                                <div class="flex items-center gap-1 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                                    </svg>
                                    <span class="font-semibold text-xs text-muted">زمان مطالعه:</span>
                                    <span class="font-semibold text-xs text-foreground">{{$blog->study_time}} دقیقه</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end article:card -->

                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- end articles -->
</div>
