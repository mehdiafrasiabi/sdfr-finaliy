<?php


namespace App\Http\Controllers\Manager;


use App\Http\Controllers\Controller;

use App\Models\Question;

use App\Models\CcSubject;

use App\Models\CcChapter;

use App\Models\CcTopic;

use Illuminate\Http\Request;


class QuestionPrintController extends Controller

{

    public function print(Request $request)

    {

        $request->validate([

            'subject' => 'required|exists:cc_subjects,id',

            'chapter' => 'nullable|exists:cc_chapters,id',

            'topic' => 'nullable|exists:cc_topics,id',

            'difficulty' => 'nullable|in:easy,medium,hard,special',

            'with_answers' => 'nullable|in:0,1',

            'with_explanations' => 'nullable|in:0,1',

        ]);


        $subjectId = $request->get('subject');

        $chapterId = $request->get('chapter');

        $topicId = $request->get('topic');

        $difficulty = $request->get('difficulty');

        $withAnswers = $request->get('with_answers', '1') === '1';

        $withExplanations = $request->get('with_explanations', '0') === '1';


        // Get subject info

        $subject = CcSubject::with(['grade.educationLevel', 'field'])->find($subjectId);


        // Build question query

        $query = Question::with(['content', 'options', 'topic.chapter.subject', 'chapter.subject'])
            ->where(function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId)
                    ->orWhereHas('topic.chapter', function ($chapterQuery) use ($subjectId) {
                        $chapterQuery->where('cc_subject_id', $subjectId);
                    })
                    ->orWhereHas('chapter', function ($chapterQuery) use ($subjectId) {
                        $chapterQuery->where('cc_subject_id', $subjectId);
                    });
            });
        if ($chapterId) {
            $query->where(function ($q) use ($chapterId) {
                $q->where('cc_chapter_id', $chapterId)
                    ->orWhereHas('topic', function ($topicQuery) use ($chapterId) {
                        $topicQuery->where('cc_chapter_id', $chapterId);
                    });
            });
            $chapter = CcChapter::find($chapterId);
        } else {
            $chapter = null;
        }
        if ($topicId) {
            $query->where('cc_topic_id', $topicId);
            $topic = CcTopic::find($topicId);
        } else {
            $topic = null;
        }
        if ($difficulty) {
            $query->where('difficulty', $difficulty);
        }
        $questions = $query->orderBy('code')->get();
        $difficulties = [
            'easy' => 'آسان',
            'medium' => 'متوسط',
            'hard' => 'سخت',
            'special' => 'ویژه',
        ];
        return view('livewire.manager.questions.print-pdf', compact(
            'questions',
            'subject',
            'chapter',
            'topic',
            'difficulty',
            'difficulties',
            'withAnswers',
            'withExplanations'
        ));
    }
}
