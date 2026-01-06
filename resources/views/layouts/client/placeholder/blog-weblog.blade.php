<div>
    @include('layouts.client.placeholder.base')

    <div class="max-w-7xl space-y-14 px-4 mx-auto">

        <div class="space-y-8">

            {{-- Section Title --}}

            <div class="flex items-center gap-5 bg-gradient-to-l from-secondary to-background rounded-2xl p-5">

                <div class="skeleton skeleton-circle" style="width: 48px; height: 48px;"></div>

                <div class="flex flex-col space-y-2">

                    <div class="skeleton skeleton-text-lg" style="width: 150px;"></div>

                    <div class="skeleton skeleton-text-sm" style="width: 200px;"></div>

                </div>

            </div>


            <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

                {{-- Sidebar --}}

                <aside class="md:col-span-4 lg:col-span-3 md:order-2 order-1">

                    <div class="space-y-8">

                        {{-- Categories --}}

                        <div class="bg-background border border-primary/20 rounded-xl p-6">

                            <div class="skeleton skeleton-text-lg text-center mb-3"
                                 style="width: 150px; margin: 0 auto;"></div>

                            <div class="space-y-3">

                                @for($i = 0; $i < 5; $i++)

                                    <div class="flex justify-between">

                                        <div class="skeleton skeleton-text-sm" style="width: 100px;"></div>

                                        <div class="skeleton skeleton-circle" style="width: 20px; height: 20px;"></div>

                                    </div>

                                @endfor

                            </div>

                        </div>


                        {{-- Newsletter --}}

                        <div class="bg-primary/5 border border-primary/20 p-6 rounded-xl">

                            <div class="skeleton skeleton-text-lg text-center mb-3"
                                 style="width: 150px; margin: 0 auto;"></div>

                            <div class="skeleton skeleton-text-sm mb-5" style="width: 100%;"></div>

                            <div class="space-y-3">

                                <div class="skeleton skeleton-rounded-xl" style="width: 100%; height: 40px;"></div>

                                <div class="skeleton skeleton-rounded-xl" style="width: 100%; height: 40px;"></div>

                            </div>

                        </div>

                    </div>

                </aside>


                {{-- Main Content --}}

                <div class="lg:col-span-9 md:col-span-8 md:order-1 order-2">

                    {{-- Sort & Filter --}}

                    <div class="flex items-center gap-3 mb-3">

                        <div class="skeleton skeleton-rounded-xl" style="width: 200px; height: 44px;"></div>

                    </div>


                    {{-- Articles Grid --}}

                    <div class="grid lg:grid-cols-3 sm:grid-cols-2 gap-x-5 gap-y-10">

                        @for($i = 0; $i < 6; $i++)

                            <div class="bg-background rounded-xl p-4">

                                {{-- Image --}}

                                <div class="skeleton skeleton-rounded-xl mb-3"
                                     style="width: 100%; height: 200px;"></div>


                                {{-- Title --}}

                                <div class="skeleton skeleton-text mb-3" style="width: 90%;"></div>


                                {{-- Footer --}}

                                <div class="flex items-center justify-between">

                                    <div class="skeleton skeleton-text-sm" style="width: 100px;"></div>

                                    <div class="skeleton skeleton-rounded-3xl" style="width: 60px; height: 24px;"></div>

                                </div>

                            </div>

                        @endfor

                    </div>


                    {{-- Load More --}}

                    <div class="flex justify-center mt-8">

                        <div class="skeleton skeleton-rounded-3xl" style="width: 150px; height: 44px;"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

