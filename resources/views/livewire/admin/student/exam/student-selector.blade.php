<div>
    @canany(['create_exams_for_academic_support','create_exams_for_academic_advisor'])

        <div>
            <h2>انتخاب دانش‌آموزان برای آزمون:
                <span class="text-success">{{ $exam->title }}</span>
            </h2>


            <form wire:submit.prevent="save">
                @foreach($students as $student)
                    <label class="font-bold text-white">
                        <input type="checkbox" wire:model="selectedStudents" value="{{$student->payment->order->user->id}}">
                        {{$student->user->personalInformation->name }}
                    </label>
                    <br>
                @endforeach

                <button type="submit">ذخیره</button>
            </form>

            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
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
