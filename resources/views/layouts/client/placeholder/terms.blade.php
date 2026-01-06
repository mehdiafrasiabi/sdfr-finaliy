<div class="max-w-7xl space-y-14 px-4 mx-auto">
    @include('layouts.client.placeholder.base')

    {{-- Header --}}

    <div class="max-w-xl space-y-5 mx-auto">

        <div class="flex flex-col items-center justify-center space-y-3">

            <div class="skeleton skeleton-circle" style="width: 40px; height: 40px;"></div>

            <div class="skeleton skeleton-text-lg" style="width: 200px; height: 48px;"></div>

        </div>

        <div class="skeleton skeleton-text text-center mx-auto" style="width: 90%;"></div>

    </div>


    {{-- Content Sections --}}

    <div class="max-w-5xl space-y-10 mx-auto">

        @for($section = 0; $section < 2; $section++)

            <div class="bg-background border rounded-xl space-y-5 p-5">

                {{-- Section Title --}}

                <div class="skeleton skeleton-text-lg" style="width: 250px;"></div>

                <div class="skeleton skeleton-text" style="width: 100%;"></div>

                <div class="skeleton skeleton-text" style="width: 95%;"></div>


                {{-- List Items --}}

                <div class="space-y-3">

                    @for($i = 0; $i < 4; $i++)

                        <div class="skeleton skeleton-text-sm" style="width: {{ 90 - ($i * 5) }}%;"></div>

                    @endfor

                </div>

            </div>

        @endfor



        {{-- FAQ Section --}}

        <div class="grid">

            <div class="flex flex-col items-center justify-center space-y-3 mb-8">

                <div class="skeleton skeleton-text-lg" style="width: 200px;"></div>

            </div>


            <div class="divide-y divide-border">

                @for($i = 0; $i < 5; $i++)

                    <div class="py-3">

                        <div class="skeleton skeleton-text" style="width: 85%;"></div>

                    </div>

                @endfor

            </div>

        </div>

    </div>

</div>
