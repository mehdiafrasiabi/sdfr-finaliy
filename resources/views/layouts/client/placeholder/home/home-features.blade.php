<div>
    @include('layouts.client.placeholder.base')


    <div class="relative bg-secondary rounded-3xl my">

        {{-- Title Skeleton --}}

        <div
            class="relative right-1/2 translate-x-1/2 -translate-y-1/2 inline-flex items-center justify-center h-12 bg-background border border-border rounded-2xl px-8">

            <div class="skeleton skeleton-text" style="width: 150px;"></div>

        </div>


        {{-- Features Grid Skeleton --}}

        <div class="flex flex-nowrap items-center justify-center gap-10 md:pb-10 pb-5 md:px-10 px-5 overflow-x-auto"
             dir="ltr">

            @for($i = 0; $i < 4; $i++)

                <div class="flex flex-col items-center justify-center text-center space-y-3">

                    <div class="skeleton skeleton-circle" style="width: 80px; height: 80px;"></div>

                    <div class="skeleton skeleton-text-sm" style="width: 100px;"></div>

                </div>

            @endfor

        </div>

    </div>

</div>
