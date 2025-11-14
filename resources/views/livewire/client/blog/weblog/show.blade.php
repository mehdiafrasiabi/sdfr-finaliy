<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="flex md:flex-nowrap flex-wrap items-start gap-5">
            <div class="md:w-8/12 w-full">


                    <div class="relative">
                        @php
                            $image = $blog->images->first();
                        @endphp
                        <!-- article:thumbnail -->
                        <div class="relative z-10">
                            <img src="{{ $image ? asset('blogs/'.$blog->id.'/photo/'.$image->path) : '/client/assets/images/theme/default.jpg' }}" class="max-w-full rounded-3xl" alt="{{ $blog->title }}" />
                        </div>

                        <div class="-mt-12 pt-12">
                            <div
                                class="bg-gradient-to-b from-background to-secondary rounded-b-3xl space-y-2 p-5 mx-5">
                                <!-- article:title -->
                                <h1 class="font-bold text-xl text-foreground">{{$blog->title}}</h1>

                                <!-- article:excerpt -->

                            </div>
                            <div class="space-y-10 py-5">
                                <!-- article:description -->
                                <div class="description">

                                    <p>
                                        {!! $blog->description !!}
                                    </p>
                                </div>
                                <!-- end article:description -->

                            </div>
                        </div>
                    </div>


            </div>

        </div>
    </div>
</div>
