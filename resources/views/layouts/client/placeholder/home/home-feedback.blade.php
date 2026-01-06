<div>
    @include('layouts.client.placeholder.base')

    <div class="overflow-x-hidden py-20">

        <div class="max-w-7xl px-4 mx-auto">

            <div class="md:grid md:grid-cols-12 md:gap-10 md:space-y-0 space-y-5">

                {{-- Title Section --}}

                <div class="md:col-span-4 flex items-center gap-5">

                    <div class="skeleton skeleton-circle flex-shrink-0" style="width: 48px; height: 48px;"></div>

                    <div class="flex flex-col space-y-2 flex-grow">

                        <div class="skeleton skeleton-text-lg" style="width: 100%;"></div>

                        <div class="skeleton skeleton-text" style="width: 80%;"></div>

                    </div>

                </div>


                {{-- Feedback Cards Skeleton --}}

                <div class="md:col-span-8 w-full max-w-xl mx-auto">

                    <div class="grid gap-5">

                        @for($i = 0; $i < 2; $i++)

                            <div class="bg-background border border-border rounded-2xl shadow-xl shadow-black/5 p-8">

                                <div class="skeleton skeleton-text" style="width: 100%; height: 80px;"></div>

                            </div>

                        @endfor

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
