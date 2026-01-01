<?php


namespace App\Livewire\Manager\Story;


use App\Models\Story;

use Artesaos\SEOTools\Traits\SEOTools;

use Illuminate\Support\Facades\File;

use Livewire\Component;

use Livewire\WithPagination;


class Index extends Component

{

    use WithPagination, SEOTools;


    public $filter = 'all'; // all, published, expired

    public $search = '';


    public function mount()

    {

        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()
            ->setTitle('استوری ها');

    }


    public function delete(Story $story)

    {

        $thumbnail = $story->thumbnail;

        $storyFile = $story->story;


        File::delete('stories/thumbnail/' . $thumbnail);

        File::delete('stories/story/' . $storyFile);


        $story->delete();

        $this->dispatch('success', 'با موفقیت حذف شد.');

    }


    public function changeStatus(Story $story)

    {

        $story->update(['status' => !$story->status]);

        $this->dispatch('success', 'عملیات با موفقیت انجام شد');

    }


    public function toggleStoriesDisplay()

    {

        // Toggle global stories display setting

        $currentSetting = cache('stories_display_enabled', true);

        cache(['stories_display_enabled' => !$currentSetting], now()->addYears(10));

        $this->dispatch('success', 'وضعیت نمایش استوری ها تغییر کرد');

    }


    public function render()

    {

        $query = Story::query()->latest();


        // Apply search filter

        if ($this->search) {

            $query->where('title', 'like', '%' . $this->search . '%');

        }


        // Apply status filter

        if ($this->filter === 'published') {

            $query->where('status', true)
                ->where(function ($q) {

                    $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());

                });

        } elseif ($this->filter === 'expired') {

            $query->where('expires_at', '<', now());

        }


        $stories = $query->paginate(10);

        $storiesDisplayEnabled = cache('stories_display_enabled', true);


        return view('livewire.manager.story.index', [

            'stories' => $stories,

            'storiesDisplayEnabled' => $storiesDisplayEnabled,

        ])->layout('layouts.manager.app');

    }

}
