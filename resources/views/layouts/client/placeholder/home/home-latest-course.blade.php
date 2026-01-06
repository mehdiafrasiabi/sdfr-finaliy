<div>
    @include('layouts.client.placeholder.base')


    <div class="space-y-8">

        {{-- Section Title --}}

        <div
            class="flex items-center justify-between gap-8 bg-gradient-to-l from-secondary to-background rounded-2xl p-5">

            <div class="flex items-center gap-5">

                <div class="skeleton skeleton-circle" style="width: 48px; height: 48px;"></div>

                <div class="skeleton skeleton-text-lg" style="width: 200px;"></div>

            </div>

            <div class="skeleton skeleton-rounded-3xl" style="width: 100px; height: 44px;"></div>

        </div>


        {{-- Course Cards Grid --}}

        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">

            @for($i = 0; $i < 3; $i++)

                <div class="relative">

                    {{-- Image Skeleton --}}

                    <div class="skeleton skeleton-rounded-3xl mb-3" style="width: 100%; height: 250px;"></div>


                    <div class="bg-background rounded-b-3xl -mt-12 pt-12">

                        {{-- Title Section --}}

                        <div class="bg-gradient-to-b from-background to-secondary rounded-b-3xl space-y-2 p-5 mx-5">

                            <div class="flex items-center gap-2">

                                <div class="skeleton skeleton-circle" style="width: 4px; height: 4px;"></div>

                                <div class="skeleton skeleton-text-sm" style="width: 100px;"></div>

                            </div>

                            <div class="skeleton skeleton-text" style="width: 90%;"></div>

                        </div>


                        {{-- Details Section --}}

                        <div class="space-y-3 p-5">

                            <div class="flex items-center gap-3">

                                <div class="skeleton skeleton-text-sm" style="width: 80px;"></div>

                                <div class="skeleton skeleton-circle" style="width: 4px; height: 4px;"></div>

                                <div class="skeleton skeleton-text-sm" style="width: 80px;"></div>

                            </div>


                            <div class="flex items-center justify-between">

                                <div></div>

                                <div class="skeleton skeleton-text-lg" style="width: 120px;"></div>

                            </div>


                            {{-- Buttons --}}

                            <div class="flex gap-3">

                                <div class="skeleton skeleton-rounded-3xl flex-grow" style="height: 44px;"></div>

                                <div class="skeleton skeleton-circle" style="width: 44px; height: 44px;"></div>

                            </div>

                        </div>

                    </div>

                </div>

            @endfor

        </div>

    </div>

</div>
