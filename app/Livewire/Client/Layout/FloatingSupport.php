<?php
namespace App\Livewire\Client\Layout;
use App\Models\GeneralSetting;
use Livewire\Component;
class FloatingSupport extends Component
{
    public function render()
    {
        $settings = GeneralSetting::first();
        return view('livewire.client.layout.floating-support', [
            'settings' => $settings
        ]);
    }
}
