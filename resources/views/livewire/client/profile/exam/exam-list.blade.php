<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-10">
                    <div class="space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">آزمون‌ها</div>
                        </div>
                        <div class="space-y-5" x-data="{ activeTab: 'easy'}">
                            <div class="relative overflow-x-auto">
                                <ul class="inline-flex gap-2 bg-secondary border border-border rounded-full p-1">
                                    @if(count($easyExams) > 0)
                                        <li>
                                            <button type="button" class="flex items-center gap-x-2 relative rounded-full py-2 px-4"
                                                    x-bind:class="activeTab === 'easy' ? 'text-foreground bg-background' : 'text-muted'"
                                                    x-on:click="activeTab = 'easy'">
                                                <span class="font-semibold text-sm">آسان</span>
                                            </button>
                                        </li>
                                    @endif
                                    @if(count($mediumExams) > 0)
                                        <li>
                                            <button type="button" class="flex items-center gap-x-2 relative rounded-full py-2 px-4"
                                                    x-bind:class="activeTab === 'medium' ? 'text-foreground bg-background' : 'text-muted'"
                                                    x-on:click="activeTab = 'medium'">
                                                <span class="font-semibold text-sm">متوسط</span>
                                            </button>
                                        </li>
                                    @endif
                                    @if(count($hardExams) > 0)
                                        <li>
                                            <button type="button" class="flex items-center gap-x-2 relative rounded-full py-2 px-4"
                                                    x-bind:class="activeTab === 'hard' ? 'text-foreground bg-background' : 'text-muted'"
                                                    x-on:click="activeTab = 'hard'">
                                                <span class="font-semibold text-sm">سخت</span>
                                            </button>
                                        </li>
                                    @endif
                                    @if(count( $comprehensiveExams) > 0)
                                        <li>
                                            <button type="button" class="flex items-center gap-x-2 relative rounded-full py-2 px-4"
                                                    x-bind:class="activeTab === 'comprehensive' ? 'text-foreground bg-background' : 'text-muted'"
                                                    x-on:click="activeTab = 'comprehensive'">
                                                <span class="font-semibold text-sm">جامع</span>
                                            </button>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                            <div>
                                <div x-show="activeTab === 'easy'">
                                    <div class="space-y-5">
                                        @forelse($easyExams as $exam)
                                            @include('livewire.client.profile.exam.exam-card', ['exam' => $exam])
                                        @empty
                                            <p class="text-white text-center">هیچ آزمونی در سطح آسان پیدا نشد.</p>
                                        @endforelse
                                    </div>
                                </div>
                                <div x-show="activeTab === 'medium'">
                                    <div class="space-y-5">
                                        @forelse($mediumExams as $exam)
                                            @include('livewire.client.profile.exam.exam-card', ['exam' => $exam])
                                        @empty
                                            <p class="text-white text-center">هیچ آزمونی در سطح متوسط پیدا نشد.</p>
                                        @endforelse
                                    </div>
                                </div>
                                <div x-show="activeTab === 'hard'">
                                    <div class="space-y-5">
                                        @forelse($hardExams as $exam)
                                            @include('livewire.client.profile.exam.exam-card', ['exam' => $exam])
                                        @empty
                                            <p class="text-white text-center">هیچ آزمونی در سطح سخت پیدا نشد.</p>
                                        @endforelse
                                    </div>
                                </div>
                                <div x-show="activeTab === 'comprehensive'">
                                    <div class="space-y-5">
                                        @forelse($comprehensiveExams as $exam)
                                            @include('livewire.client.profile.exam.exam-card', ['exam' => $exam])
                                        @empty
                                            <p class="text-white text-center mb-3">هیچ آزمونی در سطح جامع پیدا نشد.</p>
                                            <br>
                                        @endforelse
                                    </div>
                                </div>
                                @if (empty($easyExams) && empty($mediumExams) && empty($hardExams) && empty($comprehensiveExams))
                                    <div class="flex flex-col items-center justify-center space-y-12">
                                        <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35" alt="..." />
                                        <div class="text-center space-y-3">
                                            <h2 class="font-bold text-xl text-foreground">
                                                هیچ آزمونی برای شما در نظر گرفته نشده است.
                                            </h2>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($confirmingExamId)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:model="$confirmingExamId" x-data="{ modalOpen: @entangle('confirmingExamId') }">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative w-full max-w-sm my-20 overflow-hidden transition-all transform bg-background border border-border rounded-2xl shadow-2xl z-20">
                    <div class="relative p-4">
                        <button type="button" wire:click="$set('confirmingExamId', null)"
                                class="absolute left-4 text-muted-foreground focus:outline-none hover:text-error">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col items-center justify-center space-y-5">
                            <div class="flex items-center justify-center w-14 h-14 bg-success rounded-full text-error-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-edit">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-foreground">آیا آماده شروع آزمون هستید؟</h3>
                        </div>
                    </div>
                    <div class="flex items-center gap-x-4 border-t border-border p-4">
                        <button type="button" wire:click="$set('confirmingExamId', null)"
                                class="flex items-center justify-center gap-x-2 w-full bg-background border border-border rounded-xl text-foreground py-2 px-4">
                            <span class="font-bold text-xs">لغو</span>
                        </button>
                        <button wire:click="enterExam"
                                class="flex items-center justify-center gap-x-2 w-full bg-green-500 border border-transparent rounded-xl text-error-foreground py-2 px-4">
                            <div class="font-bold text-xs">آره، شروع کنیم</div>
                        </button>
                    </div>
                </div>
                <div x-show="modalOpen" class="fixed inset-0 bg-secondary/80 cursor-pointer transition-all z-10"></div>
            </div>
        </div>
    @endif
</div>

