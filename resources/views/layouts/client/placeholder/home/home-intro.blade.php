<div style="margin-top: 0px">
    @include('layouts.client.placeholder.base')

    <div class="bg-gradient-to-l from-secondary to-background rounded-2xl p-5">

        <div class="flex md:flex-nowrap flex-wrap md:flex-row flex-col items-center justify-center gap-10 py-16">

            <div class="space-y-5 w-full md:w-auto">

                {{-- Title Skeleton --}}

                <div class="skeleton skeleton-text-lg" style="width: 80%; max-width: 500px; height: 3rem;"></div>

                <div class="skeleton skeleton-text-lg" style="width: 60%; max-width: 400px; height: 3rem;"></div>


                {{-- Description Skeleton --}}

                <div class="space-y-3">

                    <div class="skeleton skeleton-text" style="width: 100%; max-width: 600px;"></div>

                    <div class="skeleton skeleton-text" style="width: 90%; max-width: 550px;"></div>

                    <div class="skeleton skeleton-text" style="width: 70%; max-width: 450px;"></div>

                </div>


                {{-- Button Skeleton --}}

                <div class="skeleton skeleton-rounded-3xl" style="width: 150px; height: 44px;"></div>

            </div>


            {{-- Image Skeleton --}}

            <div class="flex-shrink-0 flex justify-center md:w-72 w-full md:order-2 -order-1">

                <div class="skeleton skeleton-rounded-2xl" style="width: 288px; height: 288px;"></div>

            </div>

        </div>

    </div>

</div>
