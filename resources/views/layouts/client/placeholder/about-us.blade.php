<div>
    @include('layouts.client.placeholder.base')
    <section class="py-5">

        <div class="container mx-auto px-4">

            {{-- Our Story Section --}}

            <section class="mb-20">

                <div class="flex flex-col md:flex-row items-center gap-8">

                    {{-- Text Section --}}

                    <div class="md:w-1/2">

                        <div class="skeleton skeleton-text-lg mb-3" style="width: 200px; height: 48px;"></div>



                        <div class="space-y-3 mb-6">

                            <div class="skeleton skeleton-text" style="width: 100%;"></div>

                            <div class="skeleton skeleton-text" style="width: 95%;"></div>

                            <div class="skeleton skeleton-text" style="width: 90%;"></div>

                            <div class="skeleton skeleton-text" style="width: 85%;"></div>

                        </div>



                        <div class="space-y-3 mb-6">

                            <div class="skeleton skeleton-text" style="width: 100%;"></div>

                            <div class="skeleton skeleton-text" style="width: 90%;"></div>

                            <div class="skeleton skeleton-text" style="width: 80%;"></div>

                        </div>



                        <div class="skeleton skeleton-rounded-xl" style="width: 200px; height: 48px;"></div>

                    </div>



                    {{-- Image Section --}}

                    <div class="md:w-4/12">

                        <div class="skeleton skeleton-rounded-2xl" style="width: 100%; height: 300px;"></div>

                    </div>

                </div>

            </section>



            {{-- Mission and Values Section --}}

            <section class="mb-20 bg-gray-100 dark:bg-zinc-900 rounded-2xl p-8 md:p-12">

                {{-- Title --}}

                <div class="text-center mb-12">

                    <div class="skeleton skeleton-rounded-3xl mx-auto mb-4" style="width: 150px; height: 32px;"></div>

                    <div class="skeleton skeleton-text-lg mx-auto" style="width: 300px;"></div>

                </div>



                {{-- Cards Grid --}}

                <div class="grid md:grid-cols-3 gap-8">

                    @for($i = 0; $i < 3; $i++)

                        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl">

                            <div class="skeleton skeleton-circle mb-4" style="width: 48px; height: 48px;"></div>

                            <div class="skeleton skeleton-text-lg mb-3" style="width: 150px;"></div>

                            <div class="space-y-2">

                                <div class="skeleton skeleton-text" style="width: 100%;"></div>

                                <div class="skeleton skeleton-text" style="width: 95%;"></div>

                                <div class="skeleton skeleton-text" style="width: 90%;"></div>

                            </div>

                        </div>

                    @endfor

                </div>

            </section>



            {{-- Statistics Section --}}

            <section class="mb-20">

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">

                    @for($i = 0; $i < 4; $i++)

                        <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl">

                            <div class="skeleton skeleton-text-lg mx-auto mb-2" style="width: 80px; height: 48px;"></div>

                            <div class="skeleton skeleton-text mx-auto" style="width: 120px;"></div>

                        </div>

                    @endfor

                </div>

            </section>



            {{-- Our Team Section --}}

            <section class="mb-12">

                {{-- Title --}}

                <div class="text-center mb-12">

                    <div class="skeleton skeleton-text-lg mx-auto mb-4" style="width: 200px;"></div>

                    <div class="skeleton skeleton-text mx-auto" style="width: 300px;"></div>

                </div>



                {{-- Team Grid --}}

                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-6">

                    @for($i = 0; $i < 4; $i++)

                        <div class="bg-white dark:bg-zinc-800 rounded-2xl overflow-hidden">

                            <div class="skeleton" style="width: 100%; height: 192px;"></div>

                            <div class="p-4">

                                <div class="skeleton skeleton-text mb-2" style="width: 80%;"></div>

                                <div class="skeleton skeleton-text-sm" style="width: 60%;"></div>

                            </div>

                        </div>

                    @endfor

                </div>

            </section>



            {{-- FAQ Section --}}

            <section class="bg-gray-100 dark:bg-zinc-900 rounded-2xl p-8 md:p-12">

                {{-- Title --}}

                <div class="text-center mb-8">

                    <div class="skeleton skeleton-text-lg mx-auto" style="width: 200px;"></div>

                </div>



                {{-- FAQ Items --}}

                <div class="divide-y divide-border max-w-4xl mx-auto">

                    @for($i = 0; $i < 6; $i++)

                        <div class="py-3">

                            <div class="skeleton skeleton-text" style="width: 90%;"></div>

                        </div>

                    @endfor

                </div>

            </section>

        </div>

    </section>

</div>


