<?php

namespace App\Livewire\Manager\Ticket;

use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use App\Models\Department as DepartmentModel;

class Department extends Component
{
    use SEOTools;
    public $name;
    public $search = '';

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('دپارتمان ها');
    }
    public function submit($formData)
    {
        $validation=Validator::make($formData,
            [
                'name' => 'required|required|max:55'
            ],[
                '*.required' => 'فیلد ضروری است',
                '*.string' => 'فرمت نوشتاری شما اشتباه است ',
                '*.max' => 'حداکثر نوشتن : 55 کارکتر',
            ]
        );
        $validation->validate();
        DepartmentModel::create([
            'name' => $this->name,
        ]);
        $this->reset('name');
        $this->dispatch('success', 'دپارتمان با موفقیت افزوده شد.');
    }


    public function delete($id)
    {
        DepartmentModel::findOrFail($id)->delete();
        $this->dispatch('warning', 'دپارتمان با موفقیت حذف شد.');

    }

    public function render()
    {
        $departments = DepartmentModel::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->get();
        return view('livewire.manager.ticket.department',[ 'departments' => $departments,])->layout('layouts.manager.app');
    }
}
