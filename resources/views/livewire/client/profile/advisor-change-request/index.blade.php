<div>
    @php
        $avatarFallback = asset('/client/assets/images/soon/soon.png');
    @endphp

    <div class="max-w-7xl space-y-10 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8 space-y-6" dir="rtl">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <h1 class="font-black text-xl text-foreground">درخواست جابجایی مشاور</h1>
                    </div>
                </div>

                @if (! $student || ! $advisor)
                    <div class="glass border border-border rounded-2xl p-6 text-center space-y-3">
                        <img src="/client/svg/empty2.svg" class="w-full max-w-[220px] mx-auto opacity-40" alt="">
                        <h2 class="font-bold text-foreground">مشاور فعالی برای شما ثبت نشده است.</h2>
                        <p class="text-sm text-muted">بعد از تخصیص مشاور، امکان ثبت درخواست جابجایی فعال می‌شود.</p>
                    </div>
                @else
                    <div class="glass border border-border rounded-2xl p-5 md:p-6 space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <img src="{{ $advisor->picture_url ?? $avatarFallback }}"
                                 alt="{{ $advisor->name }}"
                                 class="w-20 h-20 rounded-2xl object-cover border border-border">
                            <div class="flex-1 min-w-0 space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-xs text-muted">مشاور فعلی شما</span>
                                    <span class="inline-flex items-center rounded-full bg-primary/10 text-primary px-3 py-1 text-[11px] font-bold">
                                        فعال
                                    </span>
                                </div>
                                <h2 class="font-black text-lg text-foreground">{{ $advisor->name }}</h2>
                                <div class="flex flex-wrap gap-2 text-xs text-muted">
                                    @if($advisor->education)
                                        <span class="rounded-xl bg-secondary px-3 py-1.5">{{ $advisor->education }}</span>
                                    @endif
                                    @if($advisor->field_of_study)
                                        <span class="rounded-xl bg-secondary px-3 py-1.5">{{ $advisor->field_of_study }}</span>
                                    @endif
                                </div>
                                @if($advisor->bio)
                                    <p class="text-sm leading-7 text-muted">{{ $advisor->bio }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="rounded-2xl bg-secondary/60 border border-border p-4 text-xs md:text-sm leading-7 text-muted">
                            اگر زمان جلسات با برنامه شما هماهنگ نیست، پیگیری‌ها مطابق انتظار انجام نمی‌شود، یا دلیل دیگری دارید، درخواست را با توضیح کامل ثبت کنید. مدیر  آموزشی درخواست را بررسی می‌کند و نتیجه همین‌جا نمایش داده می‌شود.
                        </div>
                    </div>

                    @if($latest)
                        <div class="glass border rounded-2xl p-5 md:p-6 space-y-4
                            @class([
                                'border-amber-500/30' => $latest->status === \App\Models\AdvisorChangeRequest::STATUS_PENDING,
                                'border-emerald-500/30' => $latest->status === \App\Models\AdvisorChangeRequest::STATUS_APPROVED,
                                'border-red-500/30' => $latest->status === \App\Models\AdvisorChangeRequest::STATUS_REJECTED,
                                'border-border' => $latest->status === \App\Models\AdvisorChangeRequest::STATUS_CANCELLED_BY_STUDENT,
                            ])">
                            @if($latest->status === \App\Models\AdvisorChangeRequest::STATUS_PENDING)
                                <h2 class="font-black text-amber-600">مدیر  در حال پیگیری است و با شما تماس خواهد گرفته شد.</h2>
                            @elseif($latest->status === \App\Models\AdvisorChangeRequest::STATUS_APPROVED)
                                <h2 class="font-black text-emerald-600">با درخواست شما موافقت شد.</h2>
                                @if($latest->newAdvisor)
                                    <div class="flex items-center gap-3 rounded-2xl bg-emerald-500/10 p-4">
                                        <img src="{{ $latest->newAdvisor->picture_url ?? $avatarFallback }}" alt="{{ $latest->newAdvisor->name }}"
                                             class="w-14 h-14 rounded-xl object-cover border border-emerald-500/20">
                                        <div>
                                            <div class="text-xs text-muted">مشاور جدید شما</div>
                                            <div class="font-bold text-foreground">{{ $latest->newAdvisor->name }}</div>
                                            @if($latest->newAdvisor->field_of_study)
                                                <div class="text-xs text-muted mt-1">{{ $latest->newAdvisor->field_of_study }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @elseif($latest->status === \App\Models\AdvisorChangeRequest::STATUS_REJECTED)
                                <h2 class="font-black text-red-600">درخواست شما رد شد و امکان جابجایی وجود ندارد.</h2>
                                @if($latest->reject_reason)
                                    <p class="text-sm leading-7 text-muted">علت: {{ $latest->reject_reason }}</p>
                                @endif
                            @elseif($latest->status === \App\Models\AdvisorChangeRequest::STATUS_CANCELLED_BY_STUDENT)
                                <h2 class="font-black text-foreground">درخواست قبلی به درخواست دانش‌آموز لغو شد.</h2>
                                <p class="text-sm text-muted">می‌توانید درخواست جدید ثبت کنید.</p>
                            @endif
                        </div>
                    @endif

                    @if($canSubmit)
                        <form wire:submit="submit" class="glass border border-border rounded-2xl p-5 md:p-6 space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-muted">موضوع درخواست</label>
                                    <select wire:model.live="subject"
                                            class="w-full h-12 rounded-xl border border-border bg-background px-4 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/30">
                                        <option value="">انتخاب موضوع...</option>
                                        @foreach($subjects as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('subject') <div class="text-red-500 text-xs font-semibold">{{ $message }}</div> @enderror
                                </div>

                                @if($subject === \App\Models\AdvisorChangeRequest::SUBJECT_OTHER)
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-muted">موضوع دلخواه</label>
                                        <input type="text" wire:model.blur="subject_other"
                                               class="w-full h-12 rounded-xl border border-border bg-background px-4 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/30"
                                               placeholder="موضوع را بنویسید">
                                        @error('subject_other') <div class="text-red-500 text-xs font-semibold">{{ $message }}</div> @enderror
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-muted">متن درخواست</label>
                                <textarea wire:model.blur="request_text" rows="6"
                                          class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm leading-7 text-foreground focus:outline-none focus:ring-2 focus:ring-primary/30"
                                          placeholder="درخواست خود را با توضیح کافی بنویسید..."></textarea>
                                @error('request_text') <div class="text-red-500 text-xs font-semibold">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="submit"
                                    class="w-full md:w-auto min-h-12 inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-black text-primary-foreground transition hover:opacity-90 disabled:opacity-60">
                                <span wire:loading.remove wire:target="submit">ثبت درخواست</span>
                                <span wire:loading wire:target="submit">در حال ثبت...</span>
                            </button>
                        </form>
                    @endif

                    <div class="glass border border-border rounded-2xl p-5 md:p-6 space-y-4">
                        <h2 class="font-black text-foreground">لیست درخواست‌ها</h2>
                        <div class="space-y-3">
                            @forelse($requests as $request)
                                <div class="rounded-2xl border border-border bg-background/70 p-4 space-y-3">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <div>
                                            <div class="font-bold text-foreground">{{ $request->subject_label }}</div>
                                            <div class="text-xs text-muted mt-1">{{ jalali($request->created_at)->format('%Y/%m/%d H:i') }}</div>
                                        </div>
                                        <span class="self-start rounded-full px-3 py-1 text-[11px] font-black
                                            @class([
                                                'bg-amber-500/10 text-amber-600' => $request->status === \App\Models\AdvisorChangeRequest::STATUS_PENDING,
                                                'bg-emerald-500/10 text-emerald-600' => $request->status === \App\Models\AdvisorChangeRequest::STATUS_APPROVED,
                                                'bg-red-500/10 text-red-600' => $request->status === \App\Models\AdvisorChangeRequest::STATUS_REJECTED,
                                                'bg-secondary text-muted' => $request->status === \App\Models\AdvisorChangeRequest::STATUS_CANCELLED_BY_STUDENT,
                                            ])">
                                            {{ $request->status_label }}
                                        </span>
                                    </div>
                                    <p class="text-sm leading-7 text-muted">{{ $request->request_text }}</p>
                                    @if($request->oldAdvisor || $request->newAdvisor || $request->reject_reason)
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-muted">
                                            @if($request->newAdvisor)
                                                <div class="rounded-xl bg-secondary/60 px-3 py-2">مشاور جدید: {{ $request->newAdvisor->name }}</div>
                                            @endif
                                            @if($request->reject_reason)
                                                <div class="rounded-xl bg-red-500/10 px-3 py-2 sm:col-span-2">علت رد: {{ $request->reject_reason }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-border p-8 text-center text-sm text-muted">
                                    هنوز درخواستی ثبت نشده است.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
