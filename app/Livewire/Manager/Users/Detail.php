<?php

namespace App\Livewire\Manager\Users;

use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Detail extends Component
{
    use SEOTools;
    public User $user;

    // بستن/بازکردن پنلِ تکیِ کاربر
    public bool   $showPanelModal = false;
    public string $panelMessage   = '';

    public function mount($id)
    {
        $this->user = User::query()->findOrFail($id);
        $this->seo()->setTitle($this->user->name);
    }

    public function openPanelModal(): void
    {
        $this->panelMessage = (string) ($this->user->panel_closed_message ?? '');
        $this->showPanelModal = true;
    }

    public function closePanelModal(): void
    {
        $this->showPanelModal = false;
    }

    /** بستنِ پنلِ این کاربر با پیامِ دلخواه. */
    public function closePanel(): void
    {
        $this->user->panel_closed = true;
        $this->user->panel_closed_message = trim($this->panelMessage) ?: null;
        $this->user->save();

        $this->showPanelModal = false;
        $this->dispatch('success', 'پنلِ «' . $this->user->name . '» بسته شد.');
    }

    /** بازکردنِ پنلِ این کاربر. */
    public function openPanel(): void
    {
        $this->user->panel_closed = false;
        $this->user->save();

        $this->dispatch('success', 'پنلِ «' . $this->user->name . '» باز شد.');
    }

    public function render()
    {
        return view('livewire.manager.users.detail')->layout('layouts.manager.app');
    }
}
