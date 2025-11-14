<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Barnameh; // یا مدلی که فیلد download_token داره

class FileDownloadController extends Controller
{
    public function download($token)
    {
        $model = Barnameh::where('download_token', $token)->firstOrFail();

        $path = $model->hash_path . '/' . $model->barnameh;
        $file = Storage::disk('public_html')->path($path);

        if (!file_exists($file)) {
            abort(404);
        }

        return response()->download($file, $model->title . '.' . pathinfo($file, PATHINFO_EXTENSION));
    }
}
