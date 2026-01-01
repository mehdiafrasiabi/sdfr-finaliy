<?php


namespace App\Livewire\Manager\Story;


use App\Models\Story;

use Artesaos\SEOTools\Traits\SEOTools;

use Illuminate\Support\Facades\File;

use Intervention\Image\ImageManager;

use Intervention\Image\Drivers\Gd\Driver;

use Livewire\Component;

use Livewire\Features\SupportFileUploads\WithFileUploads;


class Edit extends Component

{

    use WithFileUploads, SEOTools;


    public Story $story;


    public $title = '';

    public $thumbnail;

    public $type = 'video';

    public $storyImage;

    public $storyVideoUrl = '';

    public $expiresAt = '';

    public $widgetTitle = '';

    public $widgetLink = '';

    public $status = false;


    // For live preview

    public $previewThumbnail = null;

    public $previewStoryImage = null;

    public $existingThumbnail = null;

    public $existingStory = null;


    public function mount(Story $story)

    {

        $this->story = $story;

        $this->title = $story->title;

        $this->type = $story->type;

        $this->expiresAt = $story->expires_at ? $story->expires_at->format('Y-m-d') : '';

        $this->widgetTitle = $story->widget_title ?? '';

        $this->widgetLink = $story->widget_link ?? '';

        $this->status = $story->status;


        // Set existing media for preview

        $this->existingThumbnail = "/stories/thumbnail/{$story->thumbnail}";


        if ($story->type === 'video') {

            $this->storyVideoUrl = $story->story;

            $this->existingStory = $story->story;

        } else {

            $this->existingStory = "/stories/story/{$story->story}";

        }


        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()
            ->setTitle('ویرایش استوری');

    }


    public function updatedThumbnail()

    {

        $this->validate([

            'thumbnail' => 'image|mimes:jpg,jpeg,png,webp,gif,svg|max:51200',

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

            'storyImage' => 'image|mimes:jpg,jpeg,png,webp,gif,svg|max:51200',

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


        // If changing to video and current story is video, restore URL

        if ($this->type === 'video' && $this->story->type === 'video') {

            $this->storyVideoUrl = $this->story->story;

        }

    }


    public function submit()

    {

        $rules = [

            'title' => 'required|string|max:100',

            'type' => 'required|in:image,video',

            'expiresAt' => 'required|date',

        ];


        $messages = [

            'title.required' => 'عنوان استوری الزامی است.',

            'title.max' => 'عنوان استوری حداکثر 100 کاراکتر میتواند باشد.',

            'type.required' => 'نوع استوری الزامی است.',

            'type.in' => 'نوع استوری باید تصویری یا ویدیویی باشد.',

            'expiresAt.required' => 'تاریخ انقضا الزامی است.',

            'expiresAt.date' => 'تاریخ انقضا معتبر نیست.',

        ];


        // Add thumbnail validation only if new thumbnail is uploaded

        if ($this->thumbnail) {

            $rules['thumbnail'] = 'image|mimes:jpg,jpeg,png,webp,gif,svg|max:51200';

            $messages['thumbnail.image'] = 'فایل کاور باید یک تصویر باشد.';

            $messages['thumbnail.mimes'] = 'فرمت های مجاز کاور: JPG, PNG, WEBP, GIF, SVG';

            $messages['thumbnail.max'] = 'حجم مجاز کاور تا 50 مگابایت میباشد.';

        }


        if ($this->type === 'image') {

            // Only require if no existing story image or type changed

            if (!$this->existingStory || $this->story->type !== 'image') {

                if (!$this->storyImage) {

                    $rules['storyImage'] = 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:51200';

                    $messages['storyImage.required'] = 'تصویر استوری الزامی است.';

                }

            }

            if ($this->storyImage) {

                $rules['storyImage'] = 'image|mimes:jpg,jpeg,png,webp,gif,svg|max:51200';

                $messages['storyImage.image'] = 'فایل استوری باید یک تصویر باشد.';

                $messages['storyImage.mimes'] = 'فرمت های مجاز تصویر: JPG, PNG, WEBP, GIF, SVG';

                $messages['storyImage.max'] = 'حجم مجاز تصویر تا 50 مگابایت میباشد.';

            }

        } else {

            $rules['storyVideoUrl'] = 'required|url';

            $messages['storyVideoUrl.required'] = 'آدرس مستقیم ویدیو الزامی است.';

            $messages['storyVideoUrl.url'] = 'آدرس ویدیو معتبر نیست.';

        }


        $this->validate($rules, $messages);


        $updateData = [

            'title' => $this->title,

            'type' => $this->type,

            'expires_at' => $this->expiresAt,

            'widget_title' => $this->widgetTitle ?: null,

            'widget_link' => $this->widgetLink ?: null,

            'status' => $this->status,

        ];


        // Process and save new thumbnail if uploaded

        if ($this->thumbnail) {

            // Delete old thumbnail

            File::delete(public_path('stories/thumbnail/' . $this->story->thumbnail));


            $updateData['thumbnail'] = $this->processAndSaveThumbnail($this->thumbnail);

        }


        // Process story media

        if ($this->type === 'image') {

            if ($this->storyImage) {

                // Delete old story file if it was an image

                if ($this->story->type === 'image') {

                    File::delete(public_path('stories/story/' . $this->story->story));

                }

                $updateData['story'] = $this->processAndSaveStoryImage($this->storyImage);

            }

        } else {

            // For video, save the URL

            $updateData['story'] = $this->storyVideoUrl;


            // Delete old story file if it was an image

            if ($this->story->type === 'image') {

                File::delete(public_path('stories/story/' . $this->story->story));

            }

        }


        $this->story->update($updateData);


        $this->dispatch('success', 'استوری با موفقیت ویرایش شد.');


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
            ->scaleDown(740, 1316)
            ->toWebp(85)
            ->save($path . '/' . $filename);


        return $filename;

    }


    public function render()

    {

        return view('livewire.manager.story.edit')->layout('layouts.manager.app');

    }

}
