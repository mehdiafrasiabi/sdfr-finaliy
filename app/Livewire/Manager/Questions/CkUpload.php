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



    public function upload(Request $request)

    {

        $request->validate([

            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',

        ]);



        $file = $request->file('upload');



        // Create directory

        $questionId = $request->route('questionId') ?? 'temp_' . uniqid();

        $path = public_path("questions/{$questionId}/images");

        File::ensureDirectoryExists($path);



        // Process image with Intervention

        $manager = new ImageManager(new Driver());

        $filename = uniqid() . '-' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';



        $manager->read($file->getRealPath())

            ->scaleDown(1200, 1200)

            ->toWebp(85)

            ->save("{$path}/{$filename}");



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
