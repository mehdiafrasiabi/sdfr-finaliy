<?php

namespace App\Livewire\Admin\ContactDocumentation;

use App\Exports\ContactDocumentationExport;
use App\Exports\ContactDocumentationTemplateExport;
use App\Imports\ContactDocumentationImport;
use App\Models\ContactDocumentation;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;


class Index extends Component
{
    use WithPagination, WithFileUploads,SEOTools;

    // ── فیلترهای لیست ──────────────────────────────────────────────
    public string $search        = '';
    public string $filterStatus  = '';
    public string $filterStudent = '';

    // ── فرم افزودن/ویرایش ──────────────────────────────────────────
    public bool   $showFormModal     = false;
    public ?int   $editingId         = null;
    public string $title             = '';
    public string $description       = '';
    public string $contact_status    = 'successful';
    public string $contact_date      = '';
    public string $respondent        = 'father';
    public ?int   $student_id        = null;

    // ── مودال حذف ──────────────────────────────────────────────────
    public bool  $showDeleteModal = false;
    public ?int  $deletingId      = null;

    // ── مودال ایمپورت اکسل ────────────────=─────────────────────────
    public bool  $showImportModal = false;
    public $importFile            = null;
    public array $importValid     = [];
    public array $importInvalid   = [];
    public bool  $importPreview   = false;

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('ثبت مستندات تماس دانش آموزان');
    }
    protected function rules(): array
    {
        return [
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'contact_status' => 'required|in:successful,unsuccessful',
            'contact_date'   => 'required|string',
            'respondent'     => 'required|in:father,mother,student,other',
            'student_id'     => 'required|exists:students,id',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required'          => 'وارد کردن عنوان الزامی است.',
            'contact_status.required' => 'وضعیت تماس الزامی است.',
            'contact_date.required'   => 'تاریخ تماس الزامی است.',
            'respondent.required'     => 'شخص پاسخگو الزامی است.',
            'student_id.required'     => 'انتخاب دانش‌آموز الزامی است.',
            'student_id.exists'       => 'دانش‌آموز انتخاب شده معتبر نیست.',
        ];
    }

    // ── باز کردن فرم جدید ──────────────────────────────────────────
    public function openCreate(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
        $this->dispatch('contactDocFormOpened', studentId: 0, contactDate: '');

    }

    // ── باز کردن فرم ویرایش ────────────────────────────────────────
    public function openEdit(int $id): void
    {
        $record = ContactDocumentation::findOrFail($id);
        $this->editingId      = $id;
        $this->title          = $record->title;
        $this->description    = $record->description ?? '';
        $this->contact_status = $record->contact_status;
        $this->contact_date   = Jalalian::fromCarbon(Carbon::parse($record->contact_date))->format('Y/m/d');
        $this->respondent     = $record->respondent;
        $this->student_id     = $record->student_id;
        $this->showFormModal  = true;
        $this->dispatch('contactDocFormOpened', studentId: (int)$this->student_id, contactDate: $this->contact_date);

    }

    // ── ذخیره (ثبت یا ویرایش) ─────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        $gregorianDate = $this->jalaliToGregorian($this->contact_date);
        if (!$gregorianDate) {
            $this->addError('contact_date', 'فرمت تاریخ صحیح نیست (مثال: 1403/01/15)');
            return;
        }

        $data = [
            'admin_id'       => auth('admin')->id(),
            'student_id'     => $this->student_id,
            'title'          => $this->title,
            'description'    => $this->description ?: null,
            'contact_status' => $this->contact_status,
            'contact_date'   => $gregorianDate,
            'respondent'     => $this->respondent,
        ];

        if ($this->editingId) {
            ContactDocumentation::findOrFail($this->editingId)->update($data);
            $this->dispatch('success', 'مستند تماس با موفقیت ویرایش شد.');
        } else {
            ContactDocumentation::create($data);
            $this->dispatch('success', 'مستند تماس با موفقیت ثبت شد.');
        }

        $this->closeFormModal();
    }

    // ── حذف ───────────────────────────────────────────────────────
    public function confirmDelete(int $id): void
    {
        $this->deletingId      = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            ContactDocumentation::findOrFail($this->deletingId)->delete();
            $this->dispatch('success', 'مستند تماس حذف شد.');
        }
        $this->showDeleteModal = false;
        $this->deletingId      = null;
    }

    // ── ایمپورت اکسل: آپلود و پیش‌نمایش ─────────────────────────
    public function uploadImport(): void
    {
        $this->validate(['importFile' => 'required|file|mimes:xlsx,xls,csv|max:5120']);

        $students = $this->getStudents();

        $importer = new ContactDocumentationImport(auth('admin')->id(), $students);
        Excel::import($importer, $this->importFile->getRealPath());

        $this->importValid   = $importer->validRows;
        $this->importInvalid = $importer->invalidRows;
        $this->importPreview = true;
    }

    // ── ایمپورت: ثبت نهایی در دیتابیس ───────────────────────────
    public function confirmImport(): void
    {
        if (empty($this->importValid)) {
            $this->dispatch('warning', 'هیچ ردیف معتبری برای ثبت وجود ندارد.');
            return;
        }

        $adminId  = auth('admin')->id();
        $students = $this->getStudents()->keyBy(fn($s) => trim($s->user?->personalInformation?->name ?? $s->user?->name ?? ''));
        $count    = 0;

        foreach ($this->importValid as $row) {
            $student = $students->get($row['student_name']);
            if (!$student) continue;

            ContactDocumentation::create([
                'admin_id'       => $adminId,
                'student_id'     => $student->id,
                'title'          => $row['title'],
                'description'    => $row['description'] ?: null,
                'contact_status' => $row['contact_status_val'],
                'contact_date'   => $row['contact_date_val'],
                'respondent'     => $row['respondent_val'],
            ]);
            $count++;
        }

        $this->dispatch('success', "{$count} مستند تماس با موفقیت ثبت شد.");
        $this->closeImportModal();
    }

    // ── دانلود قالب اکسل ─────────────────────────────────────────
    public function downloadTemplate()
    {
        $students = $this->getStudents()->map(fn($s) => [
            'name' => $s->user?->personalInformation?->name ?? $s->user?->name ?? 'نامشخص',
        ])->values()->toArray();

        return Excel::download(
            new ContactDocumentationTemplateExport($students),
            'قالب_مستندات_تماس.xlsx'
        );
    }

    // ── خروجی XLSX ───────────────────────────────────────────────
    public function exportExcel()
    {
        return Excel::download(
            new ContactDocumentationExport(
                auth('admin')->id(),
                $this->filterStudent ?: null,
                $this->filterStatus  ?: null
            ),
            'مستندات_تماس_' . now()->format('Ymd') . '.xlsx'
        );
    }

    // ── خروجی PDF ────────────────────────────────────────────────
    public function exportPdf()
    {
        $records = $this->getFilteredQuery()
            ->with(['student.user.personalInformation'])
            ->orderBy('contact_date', 'desc')
            ->get();

        $pdf = Pdf::loadView('pdf.contact-documentation', [
            'records'  => $records,
            'adminName'=> auth('admin')->user()->name,
            'date'     => Jalalian::now()->format('Y/m/d'),
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'مستندات_تماس_' . now()->format('Ymd') . '.pdf'
        );
    }

    // ── بستن مودال‌ها ─────────────────────────────────────────────
    public function closeFormModal(): void
    {
        $this->resetForm();
        $this->showFormModal = false;
    }

    public function closeImportModal(): void
    {
        $this->importFile    = null;
        $this->importValid   = [];
        $this->importInvalid = [];
        $this->importPreview = false;
        $this->showImportModal = false;
    }

    // ── کمکی‌ها ──────────────────────────────────────────────────
    private function resetForm(): void
    {
        $this->editingId      = null;
        $this->title          = '';
        $this->description    = '';
        $this->contact_status = 'successful';
        $this->contact_date   = '';
        $this->respondent     = 'father';
        $this->student_id     = null;
        $this->resetValidation();
    }

    private function getStudents()
    {
        return Student::with(['user.personalInformation'])
            ->where('advisor_id', auth('admin')->id())
            ->get();
    }

    private function getFilteredQuery()
    {
        $query = ContactDocumentation::where('admin_id', auth('admin')->id());

        if ($this->filterStudent) {
            $query->where('student_id', $this->filterStudent);
        }
        if ($this->filterStatus) {
            $query->where('contact_status', $this->filterStatus);
        }
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        return $query;
    }

    private function jalaliToGregorian(string $jalali): ?string
    {
        try {
            $normalised = str_replace('-', '/', trim($jalali));
            $j = Jalalian::fromFormat('Y/m/d', $normalised);
            return $j->toCarbon()->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function updatedSearch(): void    { $this->resetPage(); }
    public function updatedFilterStatus(): void  { $this->resetPage(); }
    public function updatedFilterStudent(): void { $this->resetPage(); }

    public function render()
    {
        $records = $this->getFilteredQuery()
            ->with(['student.user.personalInformation'])
            ->orderBy('contact_date', 'desc')
            ->paginate(15);

        $students = $this->getStudents();

        return view('livewire.admin.contact-documentation.index', [
            'records'  => $records,
            'students' => $students,
        ])->layout('layouts.admin.app');
    }
}
