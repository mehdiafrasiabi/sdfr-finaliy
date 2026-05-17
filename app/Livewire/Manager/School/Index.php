<?php

namespace App\Livewire\Manager\School;

use App\Models\School;
use App\Models\SchoolStaff;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, SEOTools;

    public $search = '';

    public $schoolId;
    public $name;
    public $code;
    public $address;
    public $public_phone;

    public $manager_name;
    public $manager_phone;
    public $deputy_name;
    public $deputy_phone;

    public function mount(): void
    {
        $this->seo()->setTitle('مدیریت مدارس');
    }

    public function submit(array $formData): void
    {
        $rules = [
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|max:50|unique:schools,code,' . ($this->schoolId ?? 'NULL') . ',id,deleted_at,NULL',
            'address'       => 'required|string|max:1000',
            'public_phone'  => 'required|regex:/^0\d{10}$/',
            'manager_name'  => 'required|string|max:255',
            'manager_phone' => 'required|regex:/^0\d{10}$/',
            'deputy_name'   => 'required|string|max:255',
            'deputy_phone'  => 'required|regex:/^0\d{10}$/',
        ];

        $messages = [
            '*.required'           => 'فیلد ضروری است',
            'code.unique'          => 'کد مدرسه تکراری است',
            'public_phone.regex'   => 'فرمت تلفن مدرسه معتبر نیست (مثلا 02112345678)',
            'manager_phone.regex'  => 'فرمت تلفن مدیر معتبر نیست',
            'deputy_phone.regex'   => 'فرمت تلفن معاون معتبر نیست',
        ];

        $validator = Validator::make($formData, $rules, $messages);
        $validator->validate();

        DB::transaction(function () use ($formData) {
            $school = School::updateOrCreate(
                ['id' => $this->schoolId],
                [
                    'name'         => $formData['name'],
                    'code'         => $formData['code'],
                    'address'      => $formData['address'],
                    'public_phone' => $formData['public_phone'],
                ]
            );

            SchoolStaff::updateOrCreate(
                ['school_id' => $school->id, 'role' => 'manager'],
                ['name' => $formData['manager_name'], 'phone' => $formData['manager_phone']]
            );

            SchoolStaff::updateOrCreate(
                ['school_id' => $school->id, 'role' => 'deputy'],
                ['name' => $formData['deputy_name'], 'phone' => $formData['deputy_phone']]
            );
        });

        $this->resetForm();
        $this->dispatch('success', 'عملیات با موفقیت انجام شد');
    }

    public function edit(int $schoolId): void
    {
        $school = School::with(['manager', 'deputy'])->find($schoolId);
        if (!$school) {
            return;
        }

        $this->schoolId      = $school->id;
        $this->name          = $school->name;
        $this->code          = $school->code;
        $this->address       = $school->address;
        $this->public_phone  = $school->public_phone;
        $this->manager_name  = $school->manager?->name;
        $this->manager_phone = $school->manager?->phone;
        $this->deputy_name   = $school->deputy?->name;
        $this->deputy_phone  = $school->deputy?->phone;
    }

    public function delete(int $schoolId): void
    {
        $school = School::find($schoolId);
        if (!$school) {
            return;
        }
        $school->delete();
        $this->dispatch('success', 'مدرسه با موفقیت حذف شد');
    }

    public function resetForm(): void
    {
        $this->reset([
            'schoolId', 'name', 'code', 'address', 'public_phone',
            'manager_name', 'manager_phone', 'deputy_name', 'deputy_phone',
        ]);
    }

    public function render()
    {
        $schools = School::query()
            ->with(['manager', 'deputy'])
            ->withCount(['supporters', 'students'])
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(10);

        return view('livewire.manager.school.index', ['schools' => $schools])
            ->layout('layouts.manager.app');
    }
}
