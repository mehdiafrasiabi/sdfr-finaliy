<?php

namespace App\Livewire\Admin\SendToSuperAdmin;

use App\Models\Admin;
use App\Models\ContactDocumentation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads,WithPagination;

    public $file;
    public $message;
    public $receiver_id;

    public function mount()
    {
        // مقدار پیش‌فرض: اولین سوپرادمین
        $this->receiver_id = Admin::role('super admin')->first()?->id;
    }

    public function sendFile()
    {
        $this->validate([
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,xlsx,pptx,zip,rar',
            'message' => 'nullable|string|max:1000',
            'receiver_id' => 'required|exists:admins,id',
        ],[
            '*.required' => 'عنوان فایل الزامی است.',
            '*.string' => 'عنوان باید به صورت متن باشد.',
            '*.max' => 'پیام ارسالی نباید بیشتر از 1000 کاراکتر باشد.',

            'file.file' => 'فایل معتبر نمی‌باشد.',
            'receiver_id.exists' => 'مدیر نامعتبر.',
            'file.max' => 'حجم فایل نباید بیشتر از ۱۰ مگابایت باشد.',
            'file.mimes' => 'فایل باید یکی از فرمت‌های مجاز باشد: pdf, doc, docx, xlsx, pptx, zip, rar, ',
        ]);

        // چک کن فقط برای super admin ارسال بشه
        $receiver = Admin::find($this->receiver_id);
        if (!$receiver || !$receiver->hasRole('super admin')) {
            $this->dispatch('error', 'شما فقط می‌توانید فایل برای سوپرادمین ارسال کنید.');
            return;
        }

        $filename = time() . '_' . $this->file->getClientOriginalName();
        $path = 'admin_files/' . $filename;
        $this->file->storeAs('admin_files', $filename, ['disk' => 'public']);

        ContactDocumentation::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->receiver_id,
            'file_path' => $path,
            'description' => $this->message,
        ]);

        $this->reset(['file', 'message']);
        $this->dispatch('success', 'فایل با موفقیت ارسال شد.');
    }
    public function deleteFile($id)
    {
        $file = ContactDocumentation::where('id', $id)
            ->where('sender_id', auth()->id())
            ->first();

        if (!$file) {
            $this->dispatch('error', 'فایل پیدا نشد یا مجاز به حذف نیستید.');
            return;
        }

        $path = public_path($file->file_path);
        if (File::exists($path)) {
            File::delete($path);
        }

        $file->delete();

        $this->dispatch('success', 'فایل با موفقیت حذف شد.');
    }
    public function render()
    {
        $superAdmins = Admin::role('super admin')->get();

        $myFiles = ContactDocumentation::with('receiver')
            ->where('sender_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.admin.send-to-super-admin.index', [
            'superAdmins' => $superAdmins,
            'myFiles' => $myFiles,])->layout('layouts.admin.app');
    }
}
