@php
    $students = collect($topStudent)
        ->filter(fn ($student) => filled($student->name) && filled($student->document))
        ->values();

    $studentItems = $students->map(function ($student) {
        $video = $student->video_url ?? $student->video ?? $student->video_link ?? null;

        return [
            'id' => $student->id,
            'name' => $student->name,
            'avatar' => asset('client/sdfr/topStudent/' . $student->document),
            'video' => $video,
        ];
    })->toArray();
@endphp

<div class="space-y-6" x-data="studentReviews(@js($studentItems))">
    <h2 class="text-center font-black text-2xl sm:text-3xl lg:text-5xl text-primary">
        قبولی‌های مدارس برتر سال‌های پیش
    </h2>

    @if(count($studentItems))
        <div class="relative rounded-3xl p-4 sm:p-6 lg:px-10 lg:py-8 bg-[linear-gradient(135deg,#ffe7c2_0%,#f5bfd0_55%,#bfcdfc_100%)] overflow-hidden">
            <div class="hidden lg:block">
                <div class="grid grid-cols-12 gap-6 items-center min-h-[720px]">
                    <div class="col-span-4 h-full">
                        <div class="grid grid-cols-2 gap-y-12 pt-6">
                            <template x-for="(student, index) in students.filter((item,idx) => idx % 2 === 0).slice(0,4)" :key="student.id">
                                <button type="button" class="group text-center" @click="setActive(students.findIndex(x => x.id === student.id))">
                                    <img :src="student.avatar" :alt="student.name" class="w-20 h-20 rounded-full mx-auto object-cover border-2 transition" :class="active.id === student.id ? 'border-primary scale-105' : 'border-white/70 grayscale group-hover:grayscale-0'">
                                    <p class="mt-3 text-xl font-semibold text-primary/90" x-text="student.name"></p>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="col-span-4">
                        <div class="rounded-[28px] overflow-hidden shadow-2xl shadow-black/25 bg-black w-full max-w-[360px] mx-auto aspect-[9/16]">
                            <template x-if="active.video">
                                <video class="w-full h-full object-cover" controls playsinline preload="metadata" :poster="active.avatar" :key="active.id + '-desktop-video'">
                                    <source :src="active.video" type="video/mp4">
                                </video>
                            </template>
                            <template x-if="!active.video">
                                <div class="w-full h-full bg-center bg-cover" :style="`background-image:url('${active.avatar}')`"></div>
                            </template>
                        </div>
                    </div>
                    <div class="col-span-4">
                        <div class="rounded-[28px] overflow-hidden shadow-2xl shadow-black/25 bg-black w-full max-w-[360px] mx-auto aspect-[9/16]">
                            <template x-if="active.video">
                                <video class="w-full h-full object-cover" controls playsinline preload="metadata" :poster="active.avatar" :key="active.id + '-desktop-video'">
                                    <source :src="active.video" type="video/mp4">
                                </video>
                            </template>
                            <template x-if="!active.video">
                                <div class="w-full h-full bg-center bg-cover" :style="`background-image:url('${active.avatar}')`"></div>
                            </template>
                        </div>
                    </div>
                        </div>
            </div>
                </div>
            </div>
<div class="lg:hidden space-y-5">
    <div class="flex items-start justify-between gap-3">
        <button type="button" class="mt-7 text-primary" @click="prev()" aria-label="قبلی">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <div class="flex-1 overflow-x-auto no-scrollbar">
            <div class="flex items-start justify-start gap-4 min-w-max px-1">
                <template x-for="(student, index) in students" :key="student.id + '-mobile-avatar'">
                    <button type="button" class="text-center w-[88px]" @click="setActive(index)">
                        <img :src="student.avatar" :alt="student.name" class="w-16 h-16 rounded-full mx-auto object-cover border-2" :class="active.id === student.id ? 'border-primary' : 'border-white/70'">
                        <p class="mt-2 text-lg leading-7 text-primary" x-text="student.name"></p>
                    </button>
                </template>
            </div>
        </div>

        <button type="button" class="mt-7 text-primary" @click="next()" aria-label="بعدی">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <div class="rounded-3xl bg-white/20 backdrop-blur-sm p-4">
        <div class="rounded-[28px] overflow-hidden shadow-2xl shadow-black/20 bg-black aspect-[9/16]">
            <template x-if="active.video">
                <video class="w-full h-full object-cover" controls playsinline preload="metadata" :poster="active.avatar" :key="active.id + '-mobile-video'">
                    <source :src="active.video" type="video/mp4">
                </video>
            </template>
            <template x-if="!active.video">
                <div class="w-full h-full bg-center bg-cover" :style="`background-image:url('${active.avatar}')`"></div>
            </template>
        </div>
    </div>
</div>
        </div>
    @else
        <div class="rounded-3xl border border-border bg-secondary/60 p-8 text-center text-muted">
            در حال حاضر دانش‌آموزی برای نمایش ثبت نشده است.
        </div>
    @endif
    @script
        <script>
            window.studentReviews = function (students) {
                return {
                    students,
                    activeIndex: 0,
                    get active() {
                        return this.students[this.activeIndex] ?? {};
                        },

                    window.studentReviews = function (students) {
                        return {
                            students,
                            activeIndex: 0,
                            get active() {
                                return this.students[this.activeIndex] ?? {};
                    },
                            next() {
                                if (!this.students.length) return;
                                this.activeIndex = (this.activeIndex + 1) % this.students.length;
                            },
                            prev() {
                                if (!this.students.length) return;
                                this.activeIndex = (this.activeIndex - 1 + this.students.length) % this.students.length;
                            }
                        }
                    }
        </script>
    @endscript
</div>
