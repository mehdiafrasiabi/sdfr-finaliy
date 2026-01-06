<?php


namespace App\Livewire\Client\Home\Story;


use App\Models\Story;

use App\Models\StoryLike;

use Livewire\Component;


class Index extends Component

{

    public $stories = [];


    public function mount()

    {

        // Only load stories if display is enabled

        $storiesDisplayEnabled = cache('stories_display_enabled', true);


        if ($storiesDisplayEnabled) {

            $this->stories = Story::query()
                ->published()
                ->with(['likes'])
                ->withCount('likes')
                ->latest()
                ->limit(15)
                ->get();

        }

    }

    public function placeholder()

    {

        return view('layouts.client.placeholder.home.home-story');

    }
    public function toggleLike($storyId)

    {

        $user = auth()->user();


        if (!$user) {

            // Redirect to login

            return redirect()->route('login');

        }


        $story = Story::find($storyId);


        if (!$story) {

            return;

        }


        $existingLike = StoryLike::where('story_id', $storyId)
            ->where('user_id', $user->id)
            ->first();


        if ($existingLike) {

            $existingLike->delete();

            $this->dispatch('story-unliked', storyId: $storyId);

        } else {

            StoryLike::create([

                'story_id' => $storyId,

                'user_id' => $user->id,

            ]);

            $this->dispatch('story-liked', storyId: $storyId);

        }


        // Refresh stories

        $this->stories = Story::query()
            ->published()
            ->with(['likes'])
            ->withCount('likes')
            ->latest()
            ->limit(15)
            ->get();

    }


    public function render()

    {

        return view('livewire.client.home.story.index')->layout('layouts.client.app');

    }

}
