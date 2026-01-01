<?php


namespace App\Livewire\Manager\Story;


use App\Models\Story;

use App\Traits\UploadFile;

use Artesaos\SEOTools\Traits\SEOTools;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Validator;

use Intervention\Image\ImageManager;

use Intervention\Image\Drivers\Gd\Driver;

use Livewire\Component;

use Livewire\Features\SupportFileUploads\WithFileUploads;


class Create extends Component

{

    use WithFileUploads, SEOTools;


    public $title = '';

    public $thumbnail;

    public $type = 'video';

    public $storyImage;

    public $storyVideoUrl = '';

    public $expiresAt = '';

    public $widgetTitle = '';

    public $widgetLink = '';


    // For live preview

    public $previewThumbnail = null;

    public $previewStoryImage = null;


    public function mount()

    {

        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()
            ->setTitle('افزودن استوری');

    }


    public function updatedThumbnail()

    {

        $this->validate([

            'thumbnail' => 'image|mimes:jpg,jpeg,png,webp,gif,svg|max:51200', // 50MB

        ], [

            'thumbnail.image' => 'فایل باید یک تصویر باشد.',

            'thumbnail.mimes' => 'فرمت های مجاز: JPG, PNG, WEBP, GIF, SVG',

            'thumbnail.max' => 'حجم مجاز فایل تا 50 مگابایت میباشد.',

        ]);


        if ($this->thumbnail) {

            $this->previewThumbnail = $this->thumbnail->temporaryUrl();

        }

    }


    public function updatedStoryImage()

    {

        $this->validate([

            'storyImage' => 'image|mimes:jpg,jpeg,png,webp,gif,svg|max:51200', // 50MB

        ], [

            'storyImage.image' => 'فایل باید یک تصویر باشد.',

            'storyImage.mimes' => 'فرمت های مجاز: JPG, PNG, WEBP, GIF, SVG',

            'storyImage.max' => 'حجم مجاز فایل تا 50 مگابایت میباشد.',

        ]);


        if ($this->storyImage) {

            $this->previewStoryImage = $this->storyImage->temporaryUrl();

        }

    }


    public function updatedType()

    {

        // Reset media when type changes

        $this->storyImage = null;

        $this->storyVideoUrl = '';

        $this->previewStoryImage = null;

    }


    public function submit()

    {

        $rules = [

            'title' => 'required|string|max:100',

            'thumbnail' => 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:51200',

            'type' => 'required|in:image,video',

            'expiresAt' => 'required|date',

        ];


        $messages = [

            'title.required' => 'عنوان استوری الزامی است.',

            'title.max' => 'عنوان استوری حداکثر 100 کاراکتر میتواند باشد.',

            'thumbnail.required' => 'تصویر کاور الزامی است.',

            'thumbnail.image' => 'فایل کاور باید یک تصویر باشد.',

            'thumbnail.mimes' => 'فرمت های مجاز کاور: JPG, PNG, WEBP, GIF, SVG',

            'thumbnail.max' => 'حجم مجاز کاور تا 50 مگابایت میباشد.',

            'type.required' => 'نوع استوری الزامی است.',

            'type.in' => 'نوع استوری باید تصویری یا ویدیویی باشد.',

            'expiresAt.required' => 'تاریخ انقضا الزامی است.',

            'expiresAt.date' => 'تاریخ انقضا معتبر نیست.',

        ];


        if ($this->type === 'image') {

            $rules['storyImage'] = 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:51200';

            $messages['storyImage.required'] = 'تصویر استوری الزامی است.';

            $messages['storyImage.image'] = 'فایل استوری باید یک تصویر باشد.';

            $messages['storyImage.mimes'] = 'فرمت های مجاز تصویر: JPG, PNG, WEBP, GIF, SVG';

            $messages['storyImage.max'] = 'حجم مجاز تصویر تا 50 مگابایت میباشد.';

        } else {

            $rules['storyVideoUrl'] = 'required|url';

            $messages['storyVideoUrl.required'] = 'آدرس مستقیم ویدیو الزامی است.';

            $messages['storyVideoUrl.url'] = 'آدرس ویدیو معتبر نیست.';

        }


        $this->validate($rules, $messages);


        // Process and save thumbnail (100x100 webp)

        $thumbnailFilename = $this->processAndSaveThumbnail($this->thumbnail);


        // Process story media

        if ($this->type === 'image') {

            $storyFilename = $this->processAndSaveStoryImage($this->storyImage);

        } else {

            // For video, extract filename from URL and save the URL path

            $storyFilename = $this->storyVideoUrl;

        }


        // Create the story

        Story::create([

            'title' => $this->title,

            'thumbnail' => $thumbnailFilename,

            'story' => $storyFilename,

            'type' => $this->type,

            'status' => false,

            'expires_at' => $this->expiresAt,

            'widget_title' => $this->widgetTitle ?: null,

            'widget_link' => $this->widgetLink ?: null,

            'admin_id' => auth('manager')->id(),

        ]);


        $this->dispatch('success', 'استوری با موفقیت اضافه شد.');


        return redirect()->route('manager.story');

    }


    private function processAndSaveThumbnail($photo)

    {

        $path = public_path('stories/thumbnail');


        if (!file_exists($path)) {

            mkdir($path, 0755, true);

        }


        $filename = pathinfo($photo->hashName(), PATHINFO_FILENAME) . '.webp';


        $manager = new ImageManager(new Driver());

        $manager->read($photo->getRealPath())
            ->cover(100, 100)
            ->toWebp(85)
            ->save($path . '/' . $filename);


        return $filename;

    }


    private function processAndSaveStoryImage($photo)

    {

        $path = public_path('stories/story');


        if (!file_exists($path)) {

            mkdir($path, 0755, true);

        }


        $filename = pathinfo($photo->hashName(), PATHINFO_FILENAME) . '.webp';


        $manager = new ImageManager(new Driver());

        $manager->read($photo->getRealPath())
            ->scaleDown(740, 1316) // Maintain aspect ratio similar to 420x740

            ->toWebp(85)
            ->save($path . '/' . $filename);


        return $filename;

    }


    public function render()

    {

        return view('livewire.manager.story.create')->layout('layouts.manager.app');

    }

}
