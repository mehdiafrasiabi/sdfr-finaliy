<div class="max-w-7xl space-y-14 px-4 mx-auto">
    @include('layouts.client.placeholder.base')

    <div
        class="lg:flex lg:items-center lg:gap-10 bg-gradient-to-l from-secondary to-background rounded-2xl sm:p-10 p-5">

        {{-- Title Section --}}

        <div class="lg:w-4/12 flex items-start gap-5 lg:mb-0 mb-8">

            <div class="skeleton skeleton-circle flex-shrink-0" style="width: 48px; height: 48px;"></div>

            <div class="flex flex-col space-y-2 flex-grow">

                <div class="skeleton skeleton-text-lg" style="width: 100%;"></div>

                <div class="skeleton skeleton-text" style="width: 90%;"></div>

                <div class="skeleton skeleton-text" style="width: 80%;"></div>

            </div>

        </div>


        {{-- Blog Cards Grid --}}

        <div class="lg:w-8/12 w-full lg:mx-auto">

            <div class="grid sm:grid-cols-2 grid-cols-1 gap-x-5 gap-y-8">

                @for($i = 0; $i < 4; $i++)

                    <div class="bg-background rounded-xl p-4 space-y-3">

                        {{-- Image Skeleton --}}

                        <div class="skeleton skeleton-rounded-xl" style="width: 100%; height: 200px;"></div>


                        {{-- Title Skeleton --}}

                        <div class="skeleton skeleton-text" style="width: 80%;"></div>


                        {{-- Footer Skeleton --}}

                        <div class="flex items-center justify-between gap-3">

                            <div class="flex items-center gap-2">

                                <div class="skeleton skeleton-circle" style="width: 32px; height: 32px;"></div>

                                <div class="skeleton skeleton-text-sm" style="width: 80px;"></div>

                            </div>

                            <div class="skeleton skeleton-rounded-3xl" style="width: 60px; height: 24px;"></div>

                        </div>

                    </div>

                @endfor

            </div>

        </div>

    </div>

</div>
