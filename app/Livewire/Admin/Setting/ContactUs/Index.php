<?php

namespace App\Livewire\Admin\Setting\ContactUs;

use App\Models\ContactUs;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function changeStatus($contactId, $value)
    {
        $validator = Validator::make(['status' => $value, 'id' => $contactId],
            [
                'id' => 'required|exists:contact_us,id',
                'status' => 'required|in:pending,completed,canceled'
            ],
            [
                '*.required' => 'فیلد اجیاری است.',
                'status.in' => 'فرمت اشتباه است',
                'id.exists' => 'وضعیت تماس نامعتبر'
            ]
        );
        $validator->validate();
        $this->resetValidation();
        $this->dispatch('success', 'با موفقیت ثبت شد');

        ContactUs::query()->updateOrCreate(
            [
                'id' => $contactId
            ],
            [
                'status' => $value
            ]);
    }

    public function getStatusColor($status)
    {
        switch ($status) {
            case 'pending':
                return 'primary-500';
            case 'canceled':
                return 'warning-700';
            case 'completed':
                return 'success-600';

        }
    }

    public function delete($contact_id)
    {
        ContactUs::query()->where('id',$contact_id)->delete();
        $this->dispatch('success','با موفقیت حدف شد');
    }

    public function render()
    {
        $contactUs = ContactUs::query();
        $contact = $contactUs->paginate(10);
        $contact->getCollection()->transform(function ($item) {
            $item->statusColor= $this->getStatusColor($item->status);
            return $item;
        });
        return view('livewire.admin.setting.contact-us.index',
            ['contactUs'=>$contact]
        )->layout('layouts.admin.app');

    }
}
