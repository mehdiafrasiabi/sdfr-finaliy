<?php

namespace App\Livewire\Admin\Blog;

use App\Traits\UploadFile;
use Illuminate\Http\Request;
use Livewire\Component;

class CkUpload extends Component
{
    use UploadFile;

    public function upload($blogId, Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');

            // ذخیره فایل به صورت webp
            $this->uploadImageInWebpFormatBlog(
                $file,
                $blogId,
                null,
                null,
                'content' // فولدر داخل بلاگ
            );

            // گرفتن نام فایل webp
            $fileName = pathinfo($file->hashName(), PATHINFO_FILENAME) . '.webp';
            $url = asset('blogs/' . $blogId . '/content/' . $fileName);

            // پاسخ برای CKEditor
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $msg = 'تصویر با موفقیت آپلود شد';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
    public function render()
    {
        return view('livewire.admin.blog.ck-upload')->layout('layouts.admin.app');
    }
}
