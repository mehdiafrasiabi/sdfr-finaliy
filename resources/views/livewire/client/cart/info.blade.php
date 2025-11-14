<div x-data="{ modalOpen: false }">
    <!-- container -->
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="flex md:flex-nowrap flex-wrap items-start gap-5">
            <div class="md:w-8/12 w-full">
                <!-- section:title -->
                <div class="flex items-center justify-between gap-8 bg-gradient-to-l from-secondary to-background rounded-2xl p-5">
                    <div class="flex items-center gap-5">
                                    <span class="flex items-center justify-center w-12 h-12 bg-primary text-primary-foreground rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M9.664 1.319a.75.75 0 0 1 .672 0 41.059 41.059 0 0 1 8.198 5.424.75.75 0 0 1-.254 1.285 31.372 31.372 0 0 0-7.86 3.83.75.75 0 0 1-.84 0 31.508 31.508 0 0 0-2.08-1.287V9.394c0-.244.116-.463.302-.592a35.504 35.504 0 0 1 3.305-2.033.75.75 0 0 0-.714-1.319 37 37 0 0 0-3.446 2.12A2.216 2.216 0 0 0 6 9.393v.38a31.293 31.293 0 0 0-4.28-1.746.75.75 0 0 1-.254-1.285 41.059 41.059 0 0 1 8.198-5.424ZM6 11.459a29.848 29.848 0 0 0-2.455-1.158 41.029 41.029 0 0 0-.39 3.114.75.75 0 0 0 .419.74c.528.256 1.046.53 1.554.82-.21.324-.455.63-.739.914a.75.75 0 1 0 1.06 1.06c.37-.369.69-.77.96-1.193a26.61 26.61 0 0 1 3.095 2.348.75.75 0 0 0 .992 0 26.547 26.547 0 0 1 5.93-3.95.75.75 0 0 0 .42-.739 41.053 41.053 0 0 0-.39-3.114 29.925 29.925 0 0 0-5.199 2.801 2.25 2.25 0 0 1-2.514 0c-.41-.275-.826-.541-1.25-.797a6.985 6.985 0 0 1-1.084 3.45 26.503 26.503 0 0 0-1.281-.78A5.487 5.487 0 0 0 6 12v-.54Z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                        <div class="flex flex-col space-y-2">
                            <span class="font-black xs:text-2xl text-lg text-primary">اطلاعات کاربری</span>
                        </div>
                    </div>
                </div>
                <!-- end section:title -->

                <!-- cart-items:wrapper -->
                <div class="divide-y divide-dashed divide-border pt-3 pb-3">
                    <div class="flex items-start gap-3 relative bg-zinc-50 dark:bg-zinc-900 border border-border rounded-xl p-5" x-show="open" x-data="{ open: true }">
                        <!-- alert:icon -->
                        <span class="text-yellow-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </span><!-- alert:icon -->

                        <!-- alert:content -->
                        <div class="flex flex-col items-start">
                            <!-- alert:title -->
                            <div class="font-bold text-sm text-yellow-500 mb-2">
                                توجه :&zwnj;
                            </div><!-- end alert:title -->

                            <!-- alert:desc -->
                            <div class="font-semibold text-xs text-zinc-400">
                                <ul>
                                    <li>لطفا اطلاعات خود را با دقت وارد کنید.</li>
                                </ul>
                            </div><!-- end alert:desc -->

                            <!-- alert:actions -->
                            <div class="flex flex-wrap items-center gap-3 mt-5">
                                <button type="button" class="flex items-center gap-x-1 text-zinc-400 underline-offset-1 hover:underline" x-on:click="open = false">
                                    <span class="font-bold text-xs">فهمیدم</span>
                                </button>
                            </div><!-- end alert:actions -->
                        </div><!-- end alert:content -->
                    </div>
                    <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))"
                          class="space-y-5">
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label for="name" class="font-medium text-xs text-muted">نام
                                    و
                                    نام خانوادگی (فارسی) :</label>
                                <sup class="text-red-500">*</sup>
                                <input type="text" id="name" name="name" wire:model="name"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                @error('name')
                                <div class="font-medium text-xs text-muted text-red-500">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="space-y-1">
                                <label for="father_name"
                                       class="font-medium text-xs text-muted">نام پدر :</label>
                                <sup class="text-red-500">*</sup>
                                <input type="text" id="father_name" dir="rtl" name="fName"
                                       wire:model="fName"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                @error('fName')
                                <div class="font-medium text-xs text-muted text-red-500">
                                    {{$message}}
                                </div>
                                @enderror

                            </div>
                            <div class="space-y-1">
                                <label for="code_mell" class="font-medium text-xs text-muted">کد ملی
                                    :</label>
                                <sup class="text-red-500">*</sup>
                                <input type="tel" id="code_mell" dir="ltr" name="codeMell"
                                       wire:model="codeMell" maxlength="10"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                @error('codeMell')
                                <div class="font-medium text-xs text-muted text-red-500">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="space-y-1">
                                <label for="place_of_birth"
                                       class="font-medium text-xs text-muted">محل تولد :</label>
                                <sup class="text-red-500">*</sup>
                                <input type="tel" id="place_of_birth" dir="rtl" name="placeOfBirth"
                                       wire:model="placeOfBirth"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                @error('placeOfBirth')
                                <div class="font-medium text-xs text-muted text-red-500">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="space-y-1">
                                <label for="birth_date"
                                       class="font-medium text-xs text-muted">تاریخ تولد :</label>
                                <sup class="text-red-500">*</sup>
                                <input type="tel" id="birth_date" dir="ltr" name="birth_date"
                                       wire:model.lazy="birth_date"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                @error('birth_date')
                                <div class="font-medium text-xs text-muted text-red-500">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="space-y-1">
                                <label for="stateId"
                                       class="font-medium text-xs text-muted">استان(محل سکونت) :</label>
                                <sup class="text-red-500">*</sup>
                                <select id="stateId" wire:model="province" name="province"
                                        wire:change="getCity($event.target.value)"
                                        class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                    @foreach($provinces as $item)
                                        <option
                                            {{$province == $item->id ? 'selected' : ''}} value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('province')
                                <div class="font-medium text-xs text-muted text-red-500">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="space-y-1">
                                <label for="cityId"
                                       class="font-medium text-xs text-muted">شهر(محل سکونت) :</label>
                                <sup class="text-red-500">*</sup>
                                <select id="cityId" name="city" wire:model="city"
                                        class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                    @foreach($cities as $item)
                                        <option
                                            {{$city == $item->id ? 'selected' : ''}} value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('city')
                                <div class="font-medium text-xs text-muted text-red-500">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            @php
                                $grades = [
                                    '10' => 'دهم',
                                    '11' => 'یازدهم',
                                    '12' => 'دوازدهم',
                                ];

                                $fields = [
                                    'math'        => 'ریاضی',
                                    'experimental'=> 'تجربی',
                                    'human'       => 'انسانی',
                                ];
                            @endphp
                            <div class="space-y-1">
                                <label for="field"
                                       class="font-medium text-xs text-muted">رشته تحصیلی :</label>
                                <sup class="text-red-500">*</sup>
                                <select id="field" name="field" wire:model="field"
                                        class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                    @foreach($fields as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('field')
                                <div class="font-medium text-xs text-muted text-red-500">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="space-y-1">
                                <label for="grade"
                                       class="font-medium text-xs text-muted">پایه  :</label>
                                <sup class="text-red-500">*</sup>
                                <select id="grade" name="grade" wire:model="grade"
                                        class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                    @foreach($grades as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('grade')
                                <div class="font-medium text-xs text-muted text-red-500">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label for="address" class="font-medium text-xs text-muted">ادرس محل سکونت
                                :</label>
                            <sup class="text-red-500">*</sup>
                            <textarea rows="5" id="address" name="address" wire:model="address"
                                      class="form-textarea w-full !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"></textarea>
                            @error('address')
                            <div class="font-medium text-xs text-muted text-red-500">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">راه های ارتباطی</div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label for="father_mobile" class="font-medium text-xs text-muted">موبایل
                                    پدر :</label>
                                <sup class="text-red-500">*</sup>
                                <input type="tel" dir="ltr" id="father_mobile" name="fMobile"
                                       wire:model="fMobile" maxlength="11"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                @error('fMobile')
                                <div class="font-medium text-xs text-muted text-red-500">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="space-y-1">
                                <label for="mother_mobile" class="font-medium text-xs text-muted">موبایل
                                    مادر :</label>
                                <sup class="text-red-500">*</sup>
                                <input type="tel" dir="ltr" id="mother_mobile" name="mMobile"
                                       wire:model="mMobile" maxlength="11"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                @error('mMobile')
                                <div class="font-medium text-xs text-muted text-red-500">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="pt-4">
                            <button type="submit"
                                    class="w-full h-11 inline-flex items-center justify-center gap-1 bg-primary rounded-full text-primary-foreground transition-all hover:opacity-80 px-4">
                                <span class="font-semibold text-sm" wire:loading.remove>تکمیل فرایند خرید</span>
                                <div wire:loading>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"
                                         width="24px" height="24px">
                                        <path stroke="none" fill="#ffffff"
                                              d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50">
                                            <animateTransform values="0 50 51.5;360 50 51.5"
                                                              keyTimes="0;1"
                                                              repeatCount="indefinite"
                                                              dur="0.8s"
                                                              type="rotate"
                                                              attributeName="transform"/>
                                        </path>
                                    </svg>
                                </div>
                            </button>
                        </div>

                    </form>
                    <!-- end cart-item -->
                </div>
                <!-- end cart-items:wrapper -->
            </div>

            <!-- cart:detail -->
            <div class="md:w-4/12 w-full md:sticky md:top-24">
                <div class="space-y-5">
                    <div class="bg-gradient-to-b from-secondary to-background rounded-2xl px-5 pb-5">
                        <div class="bg-background rounded-b-3xl space-y-2 p-5 mb-5">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1">
                                    <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                    <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                </div>
                                <div class="font-black text-foreground">اطلاعات پرداخت</div>
                            </div>
                        </div>
                        <div class="space-y-5">
                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="font-bold text-xs text-foreground">جمع کل</div>
                                    <div class="flex items-center gap-1">
                                        <span class="font-black text-base text-foreground">{{number_format($checkout['totalOriginalPrice'])}}</span>
                                        <span class="text-xs text-muted">تومان</span>
                                    </div>
                                </div>
{{--                                <div class="flex items-center justify-between gap-3">--}}
{{--                                    <div class="font-bold text-xs text-foreground">موجودی کیف پول</div>--}}
{{--                                    <div class="flex items-center gap-1">--}}
{{--                                        <span class="font-black text-base text-foreground">۵۲۰,۰۰۰</span>--}}
{{--                                        <span class="text-xs text-muted">تومان</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="flex items-center justify-between gap-3">
                                    <div class="font-bold text-xs text-foreground">تخفیف</div>
                                    <div class="flex items-center gap-1">
                                        <span class="font-black text-base text-green-500">{{number_format($checkout['discountAmount'])}}</span>
                                        <span class="text-xs text-muted">تومان</span>
                                    </div>
                                </div>
                            </div>
                            <div class="h-px bg-secondary"></div>
                            <div class="flex items-center justify-between gap-3 text-primary">
                                <div class="font-bold text-sm text-foreground">مبلغ قابل پرداخت</div>
                                <div class="flex items-center gap-1">
                                    <span class="font-black text-xl text-foreground">{{number_format($checkout['totalAmount'])}}</span>
                                    <span class="text-xs text-muted">تومان</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end cart:detail -->
        </div>
    </div>
    <!-- end container -->
    @push('link')
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css"/>
    @endpush
    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/persian-date/dist/persian-date.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
        <script>
            $(document).ready(function () {
                $("#birth_date").persianDatepicker({
                    format: "YYYY/MM/DD",
                    initialValueType: 'gregorian',
                    calendarType: 'persian',
                    autoClose: true
                });
            });
        </script>
    @endpush
</div>
