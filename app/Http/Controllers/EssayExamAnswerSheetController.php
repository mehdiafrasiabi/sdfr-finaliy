<?php

namespace App\Http\Controllers;

use App\Models\EssayExam;
use App\Models\EssayExamAssignment;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class EssayExamAnswerSheetController extends Controller
{
    /**
     * Admin/consultant download (blank answer sheet for an exam).
     */
    public function download(int $examId)
    {
        $admin = Auth::guard('admin')->user();
        abort_unless($admin, 403);

        $exam = EssayExam::where('admin_id', $admin->id)
            ->with('questions')
            ->findOrFail($examId);

        return $this->renderPdf($exam, studentName: null, examTitle: $exam->title);
    }

    /**
     * Student download (includes student name & exam title).
     */
    public function studentDownload(int $assignmentId)
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $student = Student::where('user_id', $user->id)->firstOrFail();

        $assignment = EssayExamAssignment::with('essayExam.questions')
            ->where('student_id', $student->id)
            ->findOrFail($assignmentId);

        // Only available during open window
        abort_unless($assignment->isWithinTimeWindow(), 403, 'آزمون فعال نیست.');

        return $this->renderPdf(
            $assignment->essayExam,
            studentName: $user->name,
            examTitle: $assignment->essayExam->title
        );
    }

    protected function renderPdf(EssayExam $exam, ?string $studentName, string $examTitle)
    {
        $pdf = Pdf::loadView('pdf.essay-exam-answer-sheet', [
            'exam'         => $exam,
            'studentName'  => $studentName,
            'examTitle'    => $examTitle,
            'questions'    => $exam->questions,
        ])->setPaper('a4', 'portrait');

        $pdf->getDomPDF()->getOptions()->set('isRemoteEnabled', true);
        $pdf->getDomPDF()->getOptions()->set('defaultFont', 'dejavu sans');

        $filename = 'answer-sheet-' . $exam->id . '.pdf';
        return $pdf->stream($filename);
    }
}

