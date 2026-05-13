<div dir="rtl" class="container py-4">
    <h3 class="mb-4">قیمت‌گذاری جدید</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="card p-4">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">پایه</label>
                <select wire:model="grade" class="form-select">
                    <option value="">انتخاب</option>
                    <option value="9">نهم</option>
                    <option value="10">دهم</option>
                    <option value="11">یازدهم</option>
                    <option value="12">دوازدهم</option>
                </select>
                @error('grade') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">قیمت کل (تومان)</label>
                <input type="number" wire:model="basePrice" class="form-control" placeholder="30000000">
                @error('basePrice') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-2">
                <label class="form-label">تعداد ماه</label>
                <input type="number" wire:model="monthsCount" class="form-control" min="1" max="24">
                @error('monthsCount') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-2">
                <label class="form-label">تاریخ شروع</label>
                <input type="date" wire:model="startDate" class="form-control">
                @error('startDate') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-md-2">
                <label class="form-label">تاریخ پایان</label>
                <input type="date" wire:model="endDate" class="form-control">
                @error('endDate') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="alert alert-info mt-3 small">
            توضیح: قیمت کل / تعداد ماه‌ها = مبلغی که هر ماه از قیمت کم می‌شود.
            ماه اول = قیمت کل، ماه دوم = قیمت کل منهای این مقدار، …
        </div>

        <button type="submit" class="btn btn-primary mt-3 align-self-start">ذخیره و ادامه ویرایش</button>
    </form>
</div>
