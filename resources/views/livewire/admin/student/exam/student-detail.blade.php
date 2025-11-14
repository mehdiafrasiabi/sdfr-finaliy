<div>
    @canany(['create_exams_for_academic_support','publish_exams_for_academic_advisor'])

        <div class="p-6 bg-gray-100 min-h-screen">
            <div class="max-w-5xl mx-auto">
                <div class="bg-gray-800 text-white rounded-lg shadow-2xl p-8 mb-8">
                    <h2 class="text-3xl font-bold text-center">
                        نتایج آزمون: <span class="text-success">{{ $exam->title }}</span>
                    </h2>
                    <p class="text-center text-lg mt-2 text-gray-400">
                        برای دانش‌آموز: <span class="font-semibold text-primary">{{ $student->personalInformation->name }}</span>
                    </p>
                </div>

                <div class="bg-gray-800 text-white rounded-lg shadow-2xl p-8 mb-8 text-center">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="flex flex-col items-center justify-center">
                            <p class="text-gray-400 mt-4">درصد نهایی آزمون</p>
                            <div class="relative w-48 h-48">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-5xl font-bold">{{ $percentage }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                            <div class=" bg-success rounded-lg shadow-md">
                                <p class="text-sm text-gray-400">پاسخ صحیح</p>
                                <p class="text-xl font-bold text-white">{{ $correctCount }}</p>

                            </div>
                            <div class=" bg-danger rounded-lg shadow-md">
                                <p class="text-sm text-gray-400">پاسخ نادرست</p>
                                <p class="text-xl font-bold text-white">{{ $incorrectCount }}</p>

                            </div>
                            <div class=" bg-warning rounded-lg shadow-md">
                                <p class="text-sm text-gray-400">بدون پاسخ</p>
                                <p class="text-xl font-bold text-white">{{ $unansweredCount }}</p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 p-4 bg-gray-50 rounded-lg shadow text-center">
                    @if($analysisStatus === 'sent')
                        <p class="text-green-600 font-bold text-center mb-4 btn btn-outline-success">تحلیل ارسال شده است</p>
                        <p class="text-green-600 font-bold text-center mb-4">
                            <a href="{{ asset($analysisImagePath) }}" class="mx-auto max-h-80 rounded text-center text-green-500">
                                مشاهده
                            </a>
                        </p>

                    @elseif($analysisStatus === 'expired')
                        <p class="text-red-600 font-bold text-center">مهلت ارسال تحلیل به پایان رسیده است</p>
                    @else
                        <form wire:submit.prevent="uploadAnalysis" class="text-center space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1">
                                    <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                    <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                </div>
                                <div class="font-black text-foreground">آپلود تحلیل(مهلت ارسال 48 ساعت)</div>
                            </div>
                            <div class="space-y-1">
                                <label
                                    class="inline-flex items-center gap-x-1 border rounded-full text-muted py-2.5 px-5 cursor-pointer hover:text-foreground"
                                    for="analysisPhoto" x-data="{ files: null }">
                                    <input type="file" class="sr-only" id="analysisPhoto"
                                           wire:model="analysisPhoto" accept="image/*"
                                           x-on:change="files = Object.values($event.target.files)">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                         fill="currentColor" class="size-4">
                                        <path fill-rule="evenodd"
                                              d="M11.914 4.086a2 2 0 0 0-2.828 0l-5 5a2 2 0 1 0 2.828 2.828l.556-.555a.75.75 0 0 1 1.06 1.06l-.555.556a3.5 3.5 0 0 1-4.95-4.95l5-5a3.5 3.5 0 0 1 4.95 4.95l-1.972 1.972a2.125 2.125 0 0 1-3.006-3.005L9.97 4.97a.75.75 0 1 1 1.06 1.06L9.058 8.003a.625.625 0 0 0 .884.883l1.972-1.972a2 2 0 0 0 0-2.828Z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-semibold text-xs"
                                          x-text="files ? files.map(file => file.name).join(', ') : 'بارگذاری ..'"></span>
                                </label>
                            </div>
                            @error('analysisPhoto') <span class="text-red-500">{{ $message }}</span> @enderror
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                ارسال تحلیل
                            </button>
                        </form>
                    @endif
                </div>
                <hr>
                @if (!empty($incorrectQuestions))
                    <div class="bg-gray-800 text-white rounded-lg shadow-2xl p-8 mt-10">
                        <h3 class="text-2xl font-bold text-center mb-6">سوالات با پاسخ نادرست</h3>
                        <div class="space-y-6">
                            @foreach ($incorrectQuestions as $questionNumber => $result)
                                <div class="border-b border-gray-700 pb-4">
                                    <h4 class="font-semibold text-lg text-gray-200">سوال {{ $questionNumber }}</h4>
                                    <div class="mt-2 text-sm space-y-1">
                                        <p class="text-red-400">پاسخ دانش‌آموز: <span class="font-mono">{{ $result['student_answer'] }}</span></p>
                                        <p class="text-green-400">پاسخ صحیح: <span class="font-mono">{{ $result['correct_option'] }}</span></p>
                                    </div>
                                    @if ($result['description'])
                                        <div class="mt-4 p-4 bg-gray-700 rounded-lg border border-gray-600">
                                            <p class="font-bold text-gray-300">پاسخ تشریحی:</p>
                                            <p class="text-gray-400">{{ $result['description'] }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mt-10 text-center text-gray-400 p-8 bg-gray-800 rounded-lg shadow-2xl">
                        <p>تبریک! این دانش‌آموز به همه سوالات به درستی پاسخ داده است.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-icon-left alert-light-danger alert-dismissible fade show mb-4" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <svg data-bs-dismiss="alert"> ...</svg>
            </button>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-check-square">
                <polyline points="9 11 12 14 22 4"></polyline>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
            </svg>
            <strong></strong>
            شما به این قسمت دسترسی ندارید !!!
        </div>
    @endcanany
</div>
