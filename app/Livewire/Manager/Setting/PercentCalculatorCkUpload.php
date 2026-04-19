<?php

namespace App\Livewire\Manager\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Livewire\Component;

class PercentCalculatorCkUpload extends Component
{
    public function upload(Request $request)
    {
        if (!$request->hasFile('upload')) {
            return;
        }

        $file = $request->file('upload');
        $folder = public_path('percent-calculator/content');

        File::ensureDirectoryExists($folder);

        $filename = uniqid('ck_', true) . '.webp';
        $savePath = $folder . '/' . $filename;

        $manager = new ImageManager(new Driver());
        $manager->read($file->getRealPath())
            ->scaleDown(1200)
            ->toWebp(85)
            ->save($savePath);

        $url = asset('percent-calculator/content/' . $filename);
        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $msg = 'تصویر با موفقیت آپلود شد';

        $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

        @header('Content-type: text/html; charset=utf-8');
        echo $response;
    }
}
