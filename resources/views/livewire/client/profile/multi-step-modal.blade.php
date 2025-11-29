<div class="fixed inset-0 flex items-center justify-center z-50 bg-black/60">
    <div class="relative w-full max-w-sm my-20 overflow-hidden transition-all transform bg-background border border-border rounded-2xl shadow-2xl z-20 text-white">
        <!-- Menu -->
        <h3 class="text-2xl font-extrabold mb-6 text-center border-b border-gray-700 pb-3">
            فرم پیش جلسه مشاوره
        </h3>

        <!-- Step Content -->
        <div class="space-y-6">
            @if($step === 1)
                <h4 class="text-lg font-semibold mb-3">📘 تکالیف</h4>
                <div class="space-y-4">
                    @foreach($homeworks as $i=>$hw)


                        <div class="grid sm:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label for="required_tests" class="font-medium text-xs text-muted">نام درس:</label>
                                <input type="text" wire:model="homeworks.{{$i}}.lesson"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                            </div>
                            <div class="space-y-1">
                                <label for="required_tests" class="font-medium text-xs text-muted">تعداد پارت:</label>
                                <input type="number" dir="ltr"  wire:model="homeworks.{{$i}}.parts"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                            </div>
                            <div class="space-y-1">
                                <label for="required_tests" class="font-medium text-xs text-muted">تاریخ:</label>
                                <input type="date" dir="ltr"   wire:model="homeworks.{{$i}}.date"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                            </div>

                        </div>
                    <br>
                        <hr class="border-dashed text-white">
                        <br>
                    @endforeach
                    <button wire:click="addHomework"
                            class="text-sm px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                        + افزودن تکلیف
                    </button>
                </div>
            @elseif($step === 2)
                <h4 class="text-lg font-semibold mb-3">📝 آزمون‌ها</h4>
                <div class="space-y-3">

                    @foreach($exams as $i=>$ex)
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label  class="font-medium text-xs text-muted">درس آزمون:</label>
                                <input type="text" wire:model="exams.{{$i}}.lesson"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                            </div>
                            <div class="space-y-1">
                                <label  class="font-medium text-xs text-muted">تاریخ آزمون:</label>
                                <input type="date" wire:model="exams.{{$i}}.date"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                            </div>
                        </div>

                    <br>
                        <hr class="border-dashed text-white">
                        <br>
                    @endforeach
                    <button wire:click="addExam"
                            class="text-sm px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                        + افزودن آزمون
                    </button>
                </div>

            @elseif($step === 3)
                <h4 class="text-lg font-semibold mb-3">⏰ اوقات فراغت</h4>
                <div class="space-y-4">
                    @foreach($free_times as $i=>$ft)
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label  class="font-medium text-xs text-muted">روز :</label>
                                <input type="text" wire:model="free_times.{{$i}}.day"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                            </div>
                            <div class="space-y-1">
                                <label  class="font-medium text-xs text-muted">ساعت:</label>
                                <input type="date" wire:model="free_times.{{$i}}.time"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                            </div>
                        </div>

                        <br>
                        <hr class="border-dashed text-white">
                        <br>

                    @endforeach
                    <button wire:click="addFreeTime"
                            class="text-sm px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                        + افزودن زمان
                    </button>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="mt-8 flex justify-between items-center border-t border-gray-700 pt-4">
            @if($step>1)
                <button class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-700 transition"
                        wire:click="prevStep">قبلی
                </button>
            @endif
            @if($step<3)
                <button class="ml-auto px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700 transition"
                        wire:click="nextStep">بعدی
                </button>
            @else
                <button class="ml-auto px-4 py-2 bg-green-600 rounded-lg hover:bg-green-700 transition"
                        wire:click="save">ثبت فرم ✅
                </button>
            @endif
            <button class="px-4 py-2 bg-red-600 rounded-lg hover:bg-red-700 transition"
                    wire:click="close">بستن
            </button>
        </div>
    </div>
</div>
