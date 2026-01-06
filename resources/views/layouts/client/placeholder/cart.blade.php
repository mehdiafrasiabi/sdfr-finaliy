<div class="max-w-7xl space-y-14 px-4 mx-auto">
    @include('layouts.client.placeholder.base')


    <div class="flex md:flex-nowrap flex-wrap items-start gap-5">

        {{-- Cart Items --}}

        <div class="md:w-8/12 w-full">

            {{-- Section Title --}}

            <div
                class="flex items-center justify-between gap-8 bg-gradient-to-l from-secondary to-background rounded-2xl p-5 mb-5">

                <div class="flex items-center gap-5">

                    <div class="skeleton skeleton-circle" style="width: 48px; height: 48px;"></div>

                    <div class="flex flex-col space-y-2">

                        <div class="skeleton skeleton-text-lg" style="width: 150px;"></div>

                        <div class="skeleton skeleton-text-sm" style="width: 200px;"></div>

                    </div>

                </div>

            </div>


            {{-- Cart Items List --}}

            <div class="divide-y divide-dashed divide-border">

                @for($i = 0; $i < 2; $i++)

                    <div class="flex sm:flex-nowrap flex-wrap items-start gap-8 py-6">

                        {{-- Image --}}

                        <div class="sm:w-4/12 w-full">

                            <div class="skeleton skeleton-rounded-3xl" style="width: 100%; height: 200px;"></div>

                        </div>


                        {{-- Details --}}

                        <div class="sm:w-8/12 w-full">

                            <div class="bg-gradient-to-b from-secondary to-background rounded-3xl">

                                <div class="bg-background rounded-b-3xl space-y-2 p-5 mx-5">

                                    <div class="skeleton skeleton-text-sm" style="width: 100px;"></div>

                                    <div class="skeleton skeleton-text" style="width: 90%;"></div>

                                </div>


                                <div class="space-y-3 p-5">

                                    {{-- Meta Info --}}

                                    <div class="flex items-center gap-3">

                                        <div class="skeleton skeleton-text-sm" style="width: 80px;"></div>

                                        <div class="skeleton skeleton-circle" style="width: 4px; height: 4px;"></div>

                                        <div class="skeleton skeleton-text-sm" style="width: 80px;"></div>

                                    </div>


                                    {{-- Price --}}

                                    <div class="flex items-center justify-between">

                                        <div></div>

                                        <div class="skeleton skeleton-text-lg" style="width: 120px;"></div>

                                    </div>


                                    {{-- Buttons --}}

                                    <div class="flex gap-3">

                                        <div class="skeleton skeleton-rounded-3xl flex-grow"
                                             style="height: 44px;"></div>

                                        <div class="skeleton skeleton-circle" style="width: 44px; height: 44px;"></div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @endfor

            </div>

        </div>


        {{-- Cart Summary --}}

        <div class="md:w-4/12 w-full">

            <div class="space-y-5">

                <div class="bg-gradient-to-b from-secondary to-background rounded-2xl px-5 pb-5">

                    {{-- Title --}}

                    <div class="bg-background rounded-b-3xl p-5 mb-5">

                        <div class="skeleton skeleton-text" style="width: 150px;"></div>

                    </div>


                    {{-- Discount Code --}}

                    <div class="flex items-center gap-3 mb-5">

                        <div class="skeleton skeleton-rounded-xl flex-grow" style="height: 44px;"></div>

                        <div class="skeleton skeleton-rounded-xl" style="width: 44px; height: 44px;"></div>

                    </div>


                    {{-- Price Details --}}

                    <div class="space-y-3">

                        @for($i = 0; $i < 3; $i++)

                            <div class="flex justify-between">

                                <div class="skeleton skeleton-text-sm" style="width: 100px;"></div>

                                <div class="skeleton skeleton-text" style="width: 120px;"></div>

                            </div>

                        @endfor

                    </div>

                </div>


                {{-- Checkout Button --}}

                <div class="skeleton skeleton-rounded-3xl" style="width: 100%; height: 44px;"></div>

            </div>

        </div>

    </div>

</div>
