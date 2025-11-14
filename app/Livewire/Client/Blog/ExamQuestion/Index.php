<?php

namespace App\Livewire\Client\Blog\ExamQuestion;

use App\Models\ExamCategory;
use App\Models\ExamQuestion;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination, SEOTools;

    public function mount()
    {
        $this->seo()
            ->setTitle('نمونه سوالات امتحانی');
    }

    public $categoryId = ''; // برای فیلتر دسته‌بندی

    public function updatingCategoryId()
    {
        $this->resetPage(); // وقتی دسته‌بندی تغییر کرد، بره به صفحه اول
    }

    public function render()
    {
        $examQuestions = ExamQuestion::query()
            ->with('examCategory')
            ->when($this->categoryId, function ($query) {
                $query->where('exam_category_id', $this->categoryId);
            })
            ->paginate(10);

        return view('livewire.client.blog.exam-question.index', [
            'examQuestions' => $examQuestions,
            'categories' => ExamCategory::all()
        ])->layout('layouts.client.app');
    }
}
