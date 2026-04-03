<div class="space-y-6 animate-pulse">
    <div class="h-10 w-2/3 mx-auto rounded-xl bg-muted"></div>
    <div class="rounded-3xl p-4 sm:p-6 lg:px-10 lg:py-8 bg-[linear-gradient(135deg,#ffe7c2_0%,#f5bfd0_55%,#bfcdfc_100%)]">
        <div class="hidden lg:grid grid-cols-12 gap-6 items-center min-h-[720px]">
            <div class="col-span-4 grid grid-cols-2 gap-y-12 pt-6">
                @for($i = 0; $i < 4; $i++)
                    <div class="text-center space-y-3">
                        <div class="w-20 h-20 rounded-full bg-white/60 mx-auto"></div>
                        <div class="h-5 w-28 bg-white/60 rounded mx-auto"></div>
                    </div>
                @endfor
            </div>
            <div class="col-span-4">
                <div class="max-w-[360px] mx-auto aspect-[9/16] rounded-[28px] bg-white/60"></div>
            </div>
            <div class="col-span-4 grid grid-cols-2 gap-y-12 pt-6">
                @for($i = 0; $i < 4; $i++)
                    <div class="text-center space-y-3">
                        <div class="w-20 h-20 rounded-full bg-white/60 mx-auto"></div>
                        <div class="h-5 w-28 bg-white/60 rounded mx-auto"></div>
                    </div>
                @endfor
            </div>
        </div>

        <div class="lg:hidden space-y-5">
            <div class="flex items-start justify-between gap-3">
                <div class="w-6 h-6 rounded bg-white/60 mt-7"></div>
                <div class="flex-1 flex gap-4">
                    @for($i = 0; $i < 3; $i++)
                        <div class="text-center w-[88px] space-y-2">
                            <div class="w-16 h-16 rounded-full bg-white/60 mx-auto"></div>
                            <div class="h-4 w-full rounded bg-white/60"></div>
                        </div>
                    @endfor
                </div>
                <div class="w-6 h-6 rounded bg-white/60 mt-7"></div>
            </div>
            <div class="rounded-3xl bg-white/20 p-4">
                <div class="aspect-[9/16] rounded-[28px] bg-white/60"></div>
            </div>
        </div>
    </div>
</div>
