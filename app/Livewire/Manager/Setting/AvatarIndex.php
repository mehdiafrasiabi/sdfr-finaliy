<?php

namespace App\Livewire\Manager\Setting;

use App\Models\Avatar;
use App\Models\User;
use App\Models\UserProfile;
use App\Traits\UploadFile;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class AvatarIndex extends Component
{
    use SEOTools;
    use UploadFile;
    use WithFileUploads;

    public ?int $editingId = null;
    public string $title = '';
    public string $gender = 'male';
    public int $sort_order = 0;
    public bool $is_active = true;
    public $image = null;
    public int $uploadIteration = 0;

    public function mount(): void
    {
        $this->seo()->setTitle('تنظیمات آواتار');
        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $avatar = Avatar::query()->findOrFail($id);

        $this->editingId = $avatar->id;
        $this->title = $avatar->title;
        $this->gender = $avatar->gender;
        $this->sort_order = (int) $avatar->sort_order;
        $this->is_active = (bool) $avatar->is_active;
        $this->image = null;
        $this->uploadIteration++;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $rules = [
            'title' => ['required', 'string', 'max:150'],
            'gender' => ['required', 'in:male,female'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];

        if ($this->editingId) {
            $rules['image'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];
        } else {
            $rules['image'] = ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];
        }

        $this->validate($rules, [
            'title.required' => 'عنوان آواتار الزامی است.',
            'gender.required' => 'انتخاب جنسیت الزامی است.',
            'gender.in' => 'جنسیت انتخاب‌شده معتبر نیست.',
            'sort_order.required' => 'ترتیب نمایش الزامی است.',
            'sort_order.integer' => 'ترتیب نمایش باید عدد باشد.',
            'sort_order.min' => 'ترتیب نمایش نمی‌تواند منفی باشد.',
            'image.required' => 'آپلود تصویر آواتار الزامی است.',
            'image.image' => 'فایل انتخابی باید تصویر باشد.',
            'image.mimes' => 'فرمت تصویر باید jpg، jpeg، png یا webp باشد.',
            'image.max' => 'حجم تصویر نباید بیش از ۴ مگابایت باشد.',
        ]);

        $avatar = $this->editingId
            ? Avatar::query()->findOrFail($this->editingId)
            : new Avatar();

        $avatar->fill([
            'title' => $this->title,
            'gender' => $this->gender,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'image_path' => $avatar->image_path ?: ('/avatars/tmp-' . Str::uuid() . '.webp'),
        ]);
        $avatar->save();

        if ($this->image) {
            $oldPath = $avatar->image_path;
            $avatar->image_path = $this->uploadImageInWebpFormatAvatar($this->image, $avatar->id, 800, 800);
            $avatar->save();

            User::query()->where('picture', $oldPath)->update(['picture' => $avatar->image_path]);
            UserProfile::query()->where('picture', $oldPath)->update(['picture' => $avatar->image_path]);

            $this->deleteManagedAvatarFile($oldPath);
        }

        $this->dispatch('success', $this->editingId ? 'آواتار با موفقیت ویرایش شد.' : 'آواتار جدید با موفقیت اضافه شد.');
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $avatar = Avatar::query()->findOrFail($id);

        $isInUse = User::query()->where('picture', $avatar->image_path)->exists()
            || UserProfile::query()->where('picture', $avatar->image_path)->exists();

        if (! $isInUse) {
            $this->deleteManagedAvatarFile($avatar->image_path);
        }

        $avatar->delete();

        if ($this->editingId === $id) {
            $this->resetForm();
        }

        $this->dispatch('success', 'آواتار با موفقیت حذف شد.');
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->title = '';
        $this->gender = 'male';
        $this->sort_order = (int) (Avatar::query()->max('sort_order') ?? -1) + 1;
        $this->is_active = true;
        $this->image = null;
        $this->uploadIteration++;
        $this->resetErrorBag();
    }

    private function deleteManagedAvatarFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $normalized = ltrim($path, '/');
        if (! str_starts_with($normalized, 'avatars/')) {
            return;
        }

        $fullPath = public_path($normalized);
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }

    public function render()
    {
        return view('livewire.manager.setting.avatar-index', [
            'avatars' => Avatar::query()
                ->orderBy('gender')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ])->layout('layouts.manager.app');
    }
}
