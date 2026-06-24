{{-- (B1) انتخابِ جنسیت + آواتارِ پروفایل — مشترک بین چیدمانِ موبایل و دسکتاپ --}}
<div class="space-y-4">
    <div class="relative">
        <label class="block text-xs font-semibold mb-2 text-muted">جنسیت</label>
        <div class="grid grid-cols-2 gap-3">
            <button type="button" wire:click="$set('gender','male')"
                class="gender-opt @if($gender==='male') gender-opt--boy @endif">
                <span class="gender-ava gender-ava--boy">
                    <img src="/client/assets/images/avatars/student-boy.png" alt="پسر">
                </span>
                <span class="text-sm font-bold">پسر</span>
            </button>
            <button type="button" wire:click="$set('gender','female')"
                class="gender-opt @if($gender==='female') gender-opt--girl @endif">
                <span class="gender-ava gender-ava--girl">
                    <img src="/client/assets/images/avatars/student-girl.png" alt="دختر">
                </span>
                <span class="text-sm font-bold">دختر</span>
            </button>
        </div>
        @error('gender')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
    </div>

    <div class="relative">
        <label class="block text-xs font-semibold mb-2 text-muted">آواتار پروفایل</label>
        @if($gender === '')
            <div class="text-xs text-muted bg-secondary/40 border border-border rounded-xl px-4 py-3">
                ابتدا جنسیت را انتخاب کن تا آواتارها نمایش داده شوند.
            </div>
        @else
            <button type="button" x-data @click="$dispatch('open-avatar')"
                class="w-full flex items-center gap-3 bg-secondary/50 border border-border rounded-xl px-4 py-3 hover:border-primary/50 transition-colors text-right">
                <span class="avatar-bubble avatar-bubble--{{ $gender==='male' ? 'boy' : 'girl' }}">
                    @if($avatar)
                        <img src="{{ $avatar }}" alt="آواتار">
                    @else
                        <svg class="w-6 h-6 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20a8 8 0 0 1 16 0"/></svg>
                    @endif
                </span>
                <span class="flex-1 text-sm font-semibold">{{ $avatar ? 'تغییر آواتار' : 'انتخاب آواتار' }}</span>
                <svg class="w-4 h-4 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </button>
        @endif
        @error('avatar')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
    </div>
</div>
