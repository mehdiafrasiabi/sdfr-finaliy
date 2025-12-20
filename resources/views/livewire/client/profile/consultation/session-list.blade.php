<div>
    @push('link')

        <style>

            [x-cloak] {

                display: none !important;

            }


            .sessions-card-shadow {

                box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12),
                0 10px 20px rgba(15, 23, 42, 0.04);

            }

        </style>

    @endpush



    <div class="max-w-7xl space-y-14 px-4 mx-auto"  x-data="{ showPreSessionModal: @entangle('showPreSessionModal') }">

        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">


            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">

                <!-- end user:info -->

                <!-- user:menus -->

                <livewire:client.profile.sidebar/>

                <!-- end user:menus -->
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-10">


                    <div >

                        <div>

                            {{-- Main Content --}}

                            <div class="lg:col-span-9 md:col-span-8 space-y-6">


                                {{-- عنوان صفحه --}}

                                <header>

                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                                        <div>

                                            <div class="flex items-center gap-3 mb-2">

                                                <div class="flex items-center gap-1">

                                                    <div class="w-1 h-1 bg-slate-900 dark:bg-slate-100 rounded-full"></div>

                                                    <div class="w-2 h-2 bg-slate-900 dark:bg-slate-100 rounded-full"></div>

                                                </div>

                                                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-slate-50">

                                                    جلسات مشاوره من

                                                </h1>

                                            </div>

                                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">

                                                در این بخش می‌توانید جلسات مشاوره و پیش‌جلسه‌های خود را مشاهده و مدیریت کنید.

                                            </p>

                                        </div>

                                    </div>

                                </header>


                                {{-- لیست جلسات --}}

                                <section

                                    class="sessions-card-shadow overflow-hidden rounded-2xl border border-slate-100/80

                           bg-white/95 dark:border-slate-800 dark:bg-slate-900/95"

                                >

                                    <div

                                        class="border-b border-slate-100/80 px-4 py-4 sm:px-6 sm:py-5

                               bg-slate-50/70 dark:border-slate-800 dark:bg-slate-900"

                                    >

                                        <div class="flex items-center justify-between gap-2">

                                            <h2 class="text-sm sm:text-base font-bold text-slate-900 dark:text-slate-50">

                                                لیست جلسات مشاوره

                                            </h2>


                                            @if($sessions->count() > 0)

                                                <span

                                                    class="inline-flex items-center gap-1 rounded-full bg-slate-900/5 px-3 py-1

                                           text-[11px] text-slate-600 ring-1 ring-slate-200/80

                                           dark:bg-slate-100/5 dark:text-slate-300 dark:ring-slate-700"

                                                >

                                    <span class="inline-flex h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                    {{ $sessions->total() }} جلسه فعال

                                </span>

                                            @endif

                                        </div>

                                    </div>


                                    <div class="overflow-x-auto">

                                        <table class="min-w-full text-xs sm:text-sm">

                                            <thead>

                                            <tr

                                                class="bg-slate-50 text-[11px] font-medium text-slate-600

                                       dark:bg-slate-800 dark:text-slate-200"

                                            >

                                                <th class="px-3 py-3 text-right sm:px-4">#</th>

                                                <th class="px-3 py-3 text-right sm:px-4">عنوان جلسه</th>

                                                <th class="px-3 py-3 text-right sm:px-4">تاریخ و ساعت</th>

                                                <th class="px-3 py-3 text-right sm:px-4 hidden md:table-cell">محل برگزاری</th>

                                                <th class="px-3 py-3 text-right sm:px-4">وضعیت</th>

                                                <th class="px-3 py-3 text-right sm:px-4 hidden sm:table-cell">پیش‌جلسه</th>

                                                <th class="px-3 py-3 text-right sm:px-4">عملیات</th>

                                            </tr>

                                            </thead>


                                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                                            @forelse($sessions as $session)

                                                <tr

                                                    class="hover:bg-slate-50/80 transition-colors dark:hover:bg-slate-800/70"

                                                >

                                                    {{-- ردیف --}}

                                                    <td class="px-3 py-3 sm:px-4 text-[11px] sm:text-xs text-slate-500 dark:text-slate-400">

                                                        {{ $loop->iteration }}

                                                    </td>


                                                    {{-- عنوان و توضیح --}}

                                                    <td class="px-3 py-3 sm:px-4 align-top">

                                                        <div class="text-xs sm:text-sm font-medium text-slate-900 dark:text-slate-100">

                                                            {{ $session->title }}

                                                        </div>

                                                        @if($session->description)

                                                            <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">

                                                                {{ Str::limit($session->description, 70) }}

                                                            </p>

                                                        @endif

                                                    </td>


                                                    {{-- تاریخ و ساعت --}}

                                                    <td class="px-3 py-3 sm:px-4 align-top text-xs sm:text-sm text-slate-700 dark:text-slate-100">

                                        <span class="block">

                                            {{ jalali($session->activation_date)->format('%d %B %Y') }}

                                        </span>

                                                        @if($session->session_time)

                                                            <span class="mt-0.5 block text-[11px] text-slate-500 dark:text-slate-400">

                                                ساعت {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}

                                            </span>

                                                        @endif

                                                    </td>


                                                    {{-- محل برگزاری --}}

                                                    <td class="px-3 py-3 sm:px-4 align-top hidden md:table-cell text-xs sm:text-sm">

                                                        @if($session->location_type === 'online')

                                                            <span

                                                                class="inline-flex items-center rounded-full bg-sky-100 px-2 py-0.5 text-[11px] font-medium text-sky-700

                                                       dark:bg-sky-900/50 dark:text-sky-200"

                                                            >

                                                آنلاین

                                            </span>



                                                            @if($session->skyroom_link && $session->is_active)

                                                                <a

                                                                    href="{{ $session->skyroom_link }}"

                                                                    target="_blank"

                                                                    class="mt-1 block text-[11px] text-sky-600 underline underline-offset-2 hover:text-sky-700 dark:text-sky-300"

                                                                >

                                                                    ورود به جلسه

                                                                </a>

                                                            @endif

                                                        @else

                                                            <span

                                                                class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-medium text-emerald-700

                                                       dark:bg-emerald-900/50 dark:text-emerald-200"

                                                            >

                                                حضوری

                                            </span>

                                                        @endif

                                                    </td>


                                                    {{-- وضعیت جلسه --}}

                                                    <td class="px-3 py-3 sm:px-4 align-top text-xs sm:text-sm">

                                                        @if($session->status === 'inactive')

                                                            <span

                                                                class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-700

                                                       dark:bg-slate-800 dark:text-slate-200"

                                                            >

                                                مانده به برگزاری

                                            </span>

                                                        @elseif($session->status === 'active')

                                                            <span

                                                                class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-medium text-amber-700

                                                       dark:bg-amber-900/40 dark:text-amber-200"

                                                            >

                                                در حال برگزاری

                                            </span>

                                                        @else

                                                            <span

                                                                class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-medium text-emerald-700

                                                       dark:bg-emerald-900/40 dark:text-emerald-200"

                                                            >

                                                برگزار شده

                                            </span>

                                                        @endif

                                                    </td>


                                                    {{-- وضعیت پیش‌جلسه --}}

                                                    <td class="px-3 py-3 sm:px-4 align-top hidden sm:table-cell text-xs sm:text-sm">

                                                        @if($session->preSession)

                                                            @if($session->preSession->status === 'completed')

                                                                <span

                                                                    class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-medium text-emerald-700

                                                           dark:bg-emerald-900/40 dark:text-emerald-200"

                                                                >

                                                    تکمیل شده

                                                </span>

                                                            @else

                                                                <span

                                                                    class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-medium text-amber-700

                                                           dark:bg-amber-900/40 dark:text-amber-200"

                                                                >

                                                    در انتظار تکمیل

                                                </span>

                                                            @endif

                                                        @else

                                                            <span class="text-[11px] text-slate-400 dark:text-slate-500">-</span>

                                                        @endif

                                                    </td>


                                                    {{-- عملیات --}}

                                                    <td class="px-3 py-3 sm:px-4 align-top text-xs sm:text-sm">

                                                        @if($session->canFillPreSession() && $session->preSession && $session->preSession->status !== 'completed')

                                                            <button

                                                                wire:click="openPreSessionModal({{ $session->id }})"

                                                                class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-1.5 text-[11px] sm:text-xs font-medium text-white shadow-sm

                                                       transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1

                                                       focus:ring-offset-white dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400 dark:focus:ring-offset-slate-900"

                                                            >

                                                                پر کردن پیش‌جلسه

                                                            </button>

                                                        @elseif($session->preSession)

                                                            <a

                                                                href="{{ route('client.profile.consultation.pre-session', $session->id) }}"

                                                                class="inline-flex items-center rounded-lg bg-slate-600 px-3 py-1.5 text-[11px] sm:text-xs font-medium text-white shadow-sm

                                                       transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-1

                                                       focus:ring-offset-white dark:bg-slate-700 dark:hover:bg-slate-600 dark:focus:ring-slate-500 dark:focus:ring-offset-slate-900"

                                                            >

                                                                مشاهده پیش‌جلسه

                                                            </a>

                                                        @else

                                                            <span class="text-[11px] text-slate-400 dark:text-slate-500">-</span>

                                                        @endif

                                                    </td>

                                                </tr>

                                            @empty

                                                <tr>

                                                    <td

                                                        colspan="7"

                                                        class="px-4 py-10 text-center text-xs sm:text-sm text-slate-500 dark:text-slate-400"

                                                    >

                                                        <div class="flex flex-col items-center justify-center gap-2">

                                                            <i class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">

                                                                event_busy

                                                            </i>

                                                            <p>هیچ جلسه مشاوره‌ای ثبت نشده است.</p>

                                                        </div>

                                                    </td>

                                                </tr>

                                            @endforelse

                                            </tbody>

                                        </table>

                                    </div>


                                    @if($sessions->hasPages())

                                        <div

                                            class="border-t border-slate-100/80 px-3 py-3 sm:px-4 sm:py-4

                                   bg-slate-50/70 dark:border-slate-800 dark:bg-slate-900"

                                        >

                                            {{ $sessions->links() }}

                                        </div>

                                    @endif

                                </section>

                            </div>

                        </div>

                    </div>


                    {{-- Modal تایید پیش‌جلسه --}}

                    <div

                        x-show="showPreSessionModal"

                        x-cloak

                        x-transition.opacity

                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4 sm:px-0"

                    >

                        <div

                            x-transition.scale

                            class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl

                   dark:border-slate-700 dark:bg-slate-900"

                        >

                            <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-slate-50 mb-3">

                                پر کردن پیش‌جلسه

                            </h3>


                            @if($selectedSession)

                                <p class="mb-4 text-xs sm:text-sm text-slate-600 dark:text-slate-300">

                                    آیا می‌خواهید پیش‌جلسه

                                    <strong class="text-slate-900 dark:text-slate-100">{{ $selectedSession->title }}</strong>

                                    را پر کنید؟

                                </p>



                                <p

                                    class="mb-4 rounded-xl bg-amber-50 px-3 py-2 text-[11px] text-amber-700

                           border border-amber-200/80 dark:bg-amber-900/20 dark:text-amber-200 dark:border-amber-700/80"

                                >

                                    <i class="material-symbols-outlined text-sm align-middle mr-1">warning</i>

                                    توجه: پس از رسیدن به تاریخ جلسه، امکان ویرایش پیش‌جلسه وجود نخواهد داشت.

                                </p>

                            @endif


                            <div class="mt-4 flex items-center justify-end gap-2 sm:gap-3">

                                <button

                                    wire:click="closePreSessionModal"

                                    class="inline-flex items-center justify-center rounded-lg bg-slate-500 px-4 py-1.5 text-xs sm:text-sm font-medium text-white shadow-sm

                           hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-1

                           focus:ring-offset-white dark:bg-slate-700 dark:hover:bg-slate-600 dark:focus:ring-slate-500 dark:focus:ring-offset-slate-900"

                                >

                                    انصراف

                                </button>


                                <button

                                    wire:click="confirmStartPreSession"

                                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-1.5 text-xs sm:text-sm font-medium text-white shadow-sm

                           hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1

                           focus:ring-offset-white dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400 dark:focus:ring-offset-slate-900"

                                >

                                    بله، شروع می‌کنم

                                </button>

                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>


    </div>

</div>



