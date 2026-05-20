<div class="container-fluid">
    <h4 class="mb-4">مدارس تحت پشتیبانی من</h4>

    @if($schools->isEmpty())
        <div class="alert alert-info">
            هیچ مدرسه‌ای به شما تخصیص داده نشده است. لطفاً با مدیر سامانه تماس بگیرید.
        </div>
    @else
        <div class="row g-3">
            @foreach($schools as $school)
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $school->name }}</h5>
                            <p class="text-muted mb-2 small">کد مدرسه: {{ $school->code }}</p>
                            <p class="mb-3">
                                <span class="badge bg-primary">{{ $school->students_count }} دانش‌آموز</span>
                            </p>
                            <a href="{{ route('admin.school-supporter.students', $school->id) }}"
                               class="btn btn-sm btn-primary">
                                مشاهده دانش‌آموزان
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
