<div class="max-w-7xl space-y-14 px-4 mx-auto">
    @include('layouts.client.placeholder.base')

    {{-- Section Title --}}

    <div class="flex flex-col items-start space-y-2">

        <div class="skeleton skeleton-text-lg" style="width: 200px;"></div>

        <div class="skeleton skeleton-text" style="width: 100%; max-width: 600px;"></div>

        <div class="skeleton skeleton-text" style="width: 80%; max-width: 500px;"></div>

    </div>


    <div class="grid md:grid-cols-12 gap-8">

        {{-- Contact Info --}}

        <div class="md:col-span-7 space-y-8">

            {{-- Social Media --}}

            <div class="space-y-5">

                <div class="skeleton skeleton-text" style="width: 150px;"></div>

                <div class="flex items-center gap-5">

                    @for($i = 0; $i < 3; $i++)

                        <div class="skeleton skeleton-circle" style="width: 48px; height: 48px;"></div>

                    @endfor

                </div>

            </div>


            {{-- Phone --}}

            <div class="space-y-5">

                <div class="skeleton skeleton-text" style="width: 180px;"></div>

                <div class="skeleton skeleton-text" style="width: 150px;"></div>

            </div>


            {{-- Address --}}

            <div class="space-y-5">

                <div class="skeleton skeleton-text" style="width: 200px;"></div>

                <div class="skeleton skeleton-text" style="width: 250px;"></div>

            </div>

        </div>


        {{-- Contact Form --}}

        <div class="md:col-span-5">

            <div class="bg-gradient-to-b from-secondary to-background rounded-2xl px-5 pb-5">

                {{-- Title --}}

                <div class="bg-background rounded-b-3xl p-5 mb-5">

                    <div class="skeleton skeleton-text" style="width: 150px;"></div>

                </div>


                {{-- Form Fields --}}

                <div class="space-y-5">

                    {{-- Name Field --}}

                    <div class="space-y-1">

                        <div class="skeleton skeleton-text-sm" style="width: 180px;"></div>

                        <div class="skeleton skeleton-rounded-xl" style="width: 100%; height: 44px;"></div>

                    </div>


                    {{-- Phone Field --}}

                    <div class="space-y-1">

                        <div class="skeleton skeleton-text-sm" style="width: 120px;"></div>

                        <div class="skeleton skeleton-rounded-xl" style="width: 100%; height: 44px;"></div>

                    </div>


                    {{-- Message Field --}}

                    <div class="space-y-1">

                        <div class="skeleton skeleton-text-sm" style="width: 150px;"></div>

                        <div class="skeleton skeleton-rounded-xl" style="width: 100%; height: 200px;"></div>

                    </div>


                    {{-- Submit Button --}}

                    <div class="flex justify-end">

                        <div class="skeleton skeleton-rounded-3xl" style="width: 150px; height: 44px;"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
