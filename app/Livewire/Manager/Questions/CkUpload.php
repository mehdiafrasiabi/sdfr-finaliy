<?php



namespace App\Livewire\Manager\Questions;

use App\Traits\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Livewire\Component;

class CkUpload extends Component
{
    use UploadFile;

    protected const QUESTION_IMAGE_WIDTH = 1080;

    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);
        $file = $request->file('upload');
        // Create directory
        $questionId = $request->route('questionId') ?? 'temp_' . uniqid();
        $path = public_path("questions/{$questionId}/images");
        File::ensureDirectoryExists($path);
        // Process image with Intervention
        $manager = new ImageManager(new Driver());
        $filename = uniqid() . '-' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
        $realPath = $file->getRealPath();
        $finalPath = "{$path}/{$filename}";
        try {
            $manager->read($realPath)
                ->scale(width: self::QUESTION_IMAGE_WIDTH)
                ->toWebp(85)
                ->save($finalPath);
        } catch (\Throwable $exception) {
            report($exception);

            if (File::exists($finalPath)) {
                File::delete($finalPath);
            }

            return response()->json([
                'uploaded' => 0,
                'error' => ['message' => 'تصویر داخل ادیتور قابل تبدیل نبود. فایل را با فرمت JPG یا PNG ذخیره و دوباره آپلود کنید.'],
            ], 422);
        }

        $info = getimagesize($finalPath);
        if (!$info || ($info['mime'] ?? null) !== 'image/webp' || (int)$info[0] !== self::QUESTION_IMAGE_WIDTH) {
            File::delete($finalPath);
            return response()->json([
                'uploaded' => 0,
                'error' => ['message' => 'تصویر داخل ادیتور به WebP با عرض ۱۰۸۰ تبدیل نشد. فایل را با فرمت JPG/PNG/WebP معمولی دوباره ذخیره کنید.'],
            ], 422);
        }

        $url = asset("questions/{$questionId}/images/{$filename}");
        // CKEditor 5 response format
        return response()->json([
            'url' => $url,
            'uploaded' => 1,
            'fileName' => $filename,
        ]);
    }
    public function render()
    {
        return view('livewire.manager.questions.ck-upload');
    }
}
