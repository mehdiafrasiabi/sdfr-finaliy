<?php

namespace App\Livewire\Client\Profile\EssayExam;

use App\Models\EssayExamAnswerUpload;
use App\Models\EssayExamAssignment;
use App\Models\EssayExamAttempt;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;

class EssayExamTest extends Component
{
    use WithFileUploads;

    public int $assignmentId;
    public ?EssayExamAssignment $assignment = null;
    public ?EssayExamAttempt $attempt = null;

    /** @var array */
    public array $photos = []; // staged uploads

    public const MAX_FILES = 10;
    public const MAX_FILE_SIZE_KB = 2048;     // 2MB
    public const MAX_TOTAL_SIZE_KB = 20480;   // 20MB

    public function mount(int $assignmentId): void
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $this->assignment = EssayExamAssignment::with(['essayExam.questions', 'time', 'latestAttempt.uploads'])
            ->where('student_id', $student->id)
            ->findOrFail($assignmentId);

        $this->assignmentId = $assignmentId;

        // check time window
        if (!$this->assignment->isWithinTimeWindow()) {
            session()->flash('error', 'آزمون در بازه زمانی قابل دسترسی نیست.');
            redirect()->route('client.profile.typed-exam.list')->send();
            return;
        }

        if (in_array($this->assignment->status, ['submitted', 'graded'])) {
            redirect()->route('client.profile.essay-exam.result', [
                'attemptId' => $this->assignment->latestAttempt->id,
            ])->send();
            return;
        }

        // Start or resume attempt
        $this->attempt = $this->assignment->latestAttempt;
        // بررسی اینکه دانش‌آموز قبلاً شروع کرده و تایمر شخصی‌اش تمام شده
        if ($this->attempt && $this->attempt->started_at && $this->assignment->time?->duration_minutes) {
            $individualDeadline = $this->attempt->started_at->copy()->addMinutes($this->assignment->time->duration_minutes);
            $effectiveEnd = $individualDeadline->lt($this->assignment->time->end_at) ? $individualDeadline : $this->assignment->time->end_at;
            if (now()->gte($effectiveEnd)) {
                session()->flash('error', 'زمان آزمون شما به پایان رسیده است و امکان ورود مجدد وجود ندارد.');
                redirect()->route('client.profile.typed-exam.list')->send();
                return;
            }
        }
        if (!$this->attempt) {
            $this->attempt = EssayExamAttempt::create([
                'assignment_id' => $this->assignment->id,
                'started_at'    => now(),
                'status'        => EssayExamAttempt::STATUS_IN_PROGRESS,
            ]);
            $this->assignment->update(['status' => EssayExamAssignment::STATUS_IN_PROGRESS]);
        }
    }

    protected function totalUploadedBytes(): int
    {
        return $this->attempt ? (int) $this->attempt->uploads()->sum('file_size') : 0;
    }

    public function uploadPhotos(): void
    {
        $this->validate([
            'photos'   => 'required|array|min:1|max:' . self::MAX_FILES,
            'photos.*' => 'image|max:' . self::MAX_FILE_SIZE_KB,
        ]);

        $currentCount = $this->attempt->uploads()->count();
        if ($currentCount + count($this->photos) > self::MAX_FILES) {
            $this->addError('photos', 'حداکثر ' . self::MAX_FILES . ' عکس می‌توانید ارسال کنید.');
            return;
        }

        $totalBytes = $this->totalUploadedBytes();
        $student = $this->assignment->student_id;

        $manager = extension_loaded('imagick') ? new ImageManager(new ImagickDriver()) : new ImageManager(new Driver());

        $relativeDir = "essay-exams/answers/{$student}/{$this->attempt->id}";
        $fullDir = base_path('public_html/' . $relativeDir);
        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0755, true);
        }

        foreach ($this->photos as $photo) {
            $fileBytes = $photo->getSize();
            if (($totalBytes + $fileBytes) > (self::MAX_TOTAL_SIZE_KB * 1024)) {
                $this->addError('photos', 'حجم مجموع از 20 مگابایت بیشتر است.');
                break;
            }

            $filename = uniqid('ans_') . '.webp';
            $image = $manager->read($photo->getRealPath())
                ->scaleDown(1600, 2400)
                ->toWebp(82);

            file_put_contents($fullDir . '/' . $filename, (string) $image);

            // Ensure temp file is not kept in livewire-tmp
            @unlink($photo->getRealPath());

            $sort = $this->attempt->uploads()->max('sort_order') + 1;
            EssayExamAnswerUpload::create([
                'attempt_id' => $this->attempt->id,
                'file_path'  => $relativeDir . '/' . $filename,
                'file_size'  => $fileBytes,
                'sort_order' => $sort,
            ]);

            $totalBytes += $fileBytes;
        }

        $this->photos = [];
        $this->attempt->refresh();
        session()->flash('upload_success', 'تصاویر با موفقیت آپلود شدند.');
        $this->dispatch('photos-uploaded');
    }

    public function deleteUpload(int $uploadId): void
    {
        $upload = EssayExamAnswerUpload::where('attempt_id', $this->attempt->id)->findOrFail($uploadId);
        $full = base_path('public_html/' . $upload->file_path);
        if (file_exists($full)) @unlink($full);
        $upload->delete();
        $this->attempt->refresh();
    }

    public function submitExam(): void
    {
        if ($this->attempt->uploads()->count() === 0) {
            session()->flash('error', 'حداقل یک تصویر از پاسخ‌نامه ارسال کنید.');
            return;
        }

        $this->attempt->update([
            'submitted_at' => now(),
            'status'       => EssayExamAttempt::STATUS_SUBMITTED,
        ]);
        $this->assignment->update(['status' => EssayExamAssignment::STATUS_SUBMITTED]);

        $this->redirectRoute('client.profile.essay-exam.result', [
            'attemptId' => $this->attempt->id,
        ]);
    }

    public function render()
    {
        $this->attempt->load('uploads');
        $exam = $this->assignment->essayExam;
        $remaining = $this->attempt->remaining_seconds;

        return view('livewire.client.profile.essay-exam.essay-exam-test', [
            'exam' => $exam,
            'remainingSeconds' => $remaining,
            'pdfUrl' => $exam->questionPdfUrl(),
        ])->layout('layouts.client.app');
    }
}
