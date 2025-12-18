<?php


namespace App\Livewire\Manager\ExamPeriods;


use App\Models\ExamPeriod;

use Livewire\Component;

use Livewire\WithPagination;


class ExamPeriodIndex extends Component

{

    use WithPagination;


    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $value = '';

    public int $order = 0;

    public bool $is_active = true;


    protected function rules(): array

    {

        $rules = [

            'name' => 'required|string|max:255',

            'value' => 'required|string|max:50|unique:exam_periods,value',

            'order' => 'required|integer|min:0',

            'is_active' => 'boolean',

        ];


        if ($this->editingId) {

            $rules['value'] = 'required|string|max:50|unique:exam_periods,value,' . $this->editingId;

        }


        return $rules;

    }


    protected function messages(): array

    {

        return [

            'name.required' => 'نام دوره زمانی الزامی است.',

            'value.required' => 'مقدار دوره زمانی الزامی است.',

            'value.unique' => 'این مقدار قبلاً ثبت شده است.',

            'order.required' => 'ترتیب الزامی است.',

        ];

    }


    public function openModal(): void

    {

        $this->reset(['editingId', 'name', 'value', 'order', 'is_active']);

        $this->is_active = true;

        $this->order = ExamPeriod::max('order') + 1;

        $this->showModal = true;

    }


    public function edit(int $id): void

    {

        $period = ExamPeriod::findOrFail($id);


        $this->editingId = $period->id;

        $this->name = $period->name;

        $this->value = $period->value;

        $this->order = $period->order;

        $this->is_active = $period->is_active;

        $this->showModal = true;

    }


    public function save(): void

    {

        $this->validate();


        if ($this->editingId) {

            $period = ExamPeriod::findOrFail($this->editingId);

            $period->update([

                'name' => $this->name,

                'value' => $this->value,

                'order' => $this->order,

                'is_active' => $this->is_active,

            ]);

            $this->dispatch('success', 'دوره زمانی با موفقیت ویرایش شد.');

        } else {

            ExamPeriod::create([

                'name' => $this->name,

                'value' => $this->value,

                'order' => $this->order,

                'is_active' => $this->is_active,

            ]);

            $this->dispatch('success', 'دوره زمانی با موفقیت ایجاد شد.');

        }


        $this->closeModal();

    }


    public function delete(int $id): void

    {

        $period = ExamPeriod::findOrFail($id);

        $period->delete();

        $this->dispatch('success', 'دوره زمانی با موفقیت حذف شد.');

    }


    public function toggleActive(int $id): void

    {

        $period = ExamPeriod::findOrFail($id);

        $period->update(['is_active' => !$period->is_active]);

    }


    public function closeModal(): void

    {

        $this->showModal = false;

        $this->reset(['editingId', 'name', 'value', 'order', 'is_active']);

    }


    public function render()

    {

        $periods = ExamPeriod::orderBy('order')->paginate(15);


        return view('livewire.manager.exam-periods.exam-period-index', compact('periods'))
            ->layout('layouts.manager.app');

    }

}
