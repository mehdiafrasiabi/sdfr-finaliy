<div>
    @include('layouts.client.placeholder.base')

    <section class="py-4">

        <div class="container">

            <div class="flex items-center gap-4 overflow-x-auto pb-2">

                @for($i = 0; $i < 12; $i++)

                    <div class="flex flex-col items-center gap-2 flex-shrink-0">

                        <div class="skeleton skeleton-circle" style="width: 80px; height: 80px;"></div>

                        <div class="skeleton skeleton-text-sm" style="width: 60px;"></div>

                    </div>

                @endfor

            </div>

        </div>

    </section>

</div>


