<div>
    <div>
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-6">ایجاد جلسه مشاوره جدید</h1>

            {{-- نمایش پیام موفقیت --}}
            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-md">
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- انتخاب دانش آموز --}}
                        <div class="col-span-1">
                            <label for="student_id" class="block text-sm font-medium text-gray-700">انتخاب دانش‌آموز</label>
                            <select id="student_id" wire:model="student_id"
                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">یک دانش‌آموز را انتخاب کنید...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                                @endforeach
                            </select>
                            @error('student_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- تاریخ فعال سازی --}}
                        <div class="col-span-1">
                            <label for="activation_date" class="block text-sm font-medium text-gray-700">تاریخ و زمان برگزاری</label>
                            <input type="datetime-local" id="activation_date" wire:model="activation_date"
                                   class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('activation_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- عنوان جلسه --}}
                    <div class="mt-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">عنوان جلسه</label>
                        <input type="text" id="title" wire:model="title" placeholder="مثال: برنامه‌ریزی هفتگی - هفته سوم مهر"
                               class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- لینک اسکای روم --}}
                    <div class="mt-4">
                        <label for="skyroom_link" class="block text-sm font-medium text-gray-700">لینک اسکای روم (اختیاری)</label>
                        <input type="url" id="skyroom_link" wire:model="skyroom_link" placeholder="https://www.skyroom.online/ch/example/session"
                               class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('skyroom_link') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- توضیحات --}}
                    <div class="mt-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">توضیحات (اختیاری)</label>
                        <textarea id="description" wire:model="description" rows="4"
                                  class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="نکات یا موارد مهم برای این جلسه را اینجا بنویسید..."></textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- دکمه ذخیره --}}
                    <div class="mt-6 text-left">
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            ایجاد جلسه
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
