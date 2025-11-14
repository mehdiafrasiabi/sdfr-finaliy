<?php

namespace App\Livewire\Manager\Blog;

use App\Models\ExamCategory;
use App\Models\ExamQuestion;
use App\Traits\UploadFile;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ExampleQuestion extends Component
{
    use WithFileUploads, UploadFile, WithPagination;

    public $name;
    public $photo;
    public $file;
    public $category_id;

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'photo' => 'required|image|max:2048',
            'file' => 'required|file|mimes:pdf,zip|max:10240', // 10MB
            'category_id' => 'required|exists:exam_categories,id',
        ], [
            '*.required' => 'فیلد اجباری است.',
            '*.string' => 'نوع نوشتاری شما اشتباه است.',
            'name.max' => 'حداکثر کاراکتر 255',
            'photo.max' => 'حداکثر آپلود عکس 2 مگابایت می‌باشد.',
            'file.max' => 'حداکثر فایل آپلود 10 مگابایت می‌باشد.',
        ]);

        // ساخت رکورد
        $sample = ExamQuestion::create([
            'name' => $this->name,
            'exam_category_id' => $this->category_id,
        ]);

        // ذخیره عکس در public
        if ($this->photo) {
            $this->uploadImageInWebpFormatExamQuestion($this->photo, $sample->id, 600, 400);
            $sample->image_path = "blog/example-question/{$sample->id}/images/" . pathinfo($this->photo->hashName(), PATHINFO_FILENAME) . ".webp";
        }

        // ذخیره فایل PDF یا ZIP در public
        if ($this->file) {
            $fileName = $this->file->getClientOriginalName();
            $this->file->storeAs("blog/exam-files/{$sample->id}", $fileName, 'public');
            $sample->file_path = "blog/exam-files/{$sample->id}/{$fileName}";
        }

        $sample->save();

        $this->dispatch('success', 'نمونه سوال با موفقیت ثبت شد.');
        $this->reset(['name', 'photo', 'file', 'category_id']);
    }

    public function delete($id)
    {
        $question = ExamQuestion::findOrFail($id);

        // حذف پوشه‌ها به طور کامل از public
        File::deleteDirectory(public_path("blog/example-question/{$question->id}"));
        File::deleteDirectory(public_path("blog/exam-files/{$question->id}"));

        $question->delete();

        $this->dispatch('success', 'نمونه سوال با موفقیت حذف شد.');
    }

    public function render()
    {
        return view('livewire.manager.blog.example-question', [
            'categories' => ExamCategory::all(),
            'examQuestions' => ExamQuestion::query()->with('examCategory')->paginate(10)
        ])->layout('layouts.manager.app');
    }


}
