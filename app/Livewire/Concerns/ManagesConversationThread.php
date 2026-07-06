<?php

namespace App\Livewire\Concerns;

use App\Models\ConversationMessage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;

/**
 * منطق مشترکِ یک «thread» گفتگو بین دانش‌آموز و مشاور.
 *
 * کامپوننتِ استفاده‌کننده باید:
 *   - از Livewire\WithFileUploads استفاده کند،
 *   - public ?Conversation $conversation داشته باشد،
 *   - public string $side تعریف کند ('student' یا 'advisor').
 *
 * تحویل «زنده» با polling انجام می‌شود: متد tick() هدفِ wire:poll است.
 */
trait ManagesConversationThread
{
    public string $body = '';
    public $image = null;
    public ?int $editingId = null;
    public string $editingBody = '';

    /** پیامی که در حال پاسخ به آن هستیم (ریپلای). */
    public ?int $replyToId = null;

    /** طرف مقابلِ فرستنده‌ی فعلی. */
    protected function otherSide(): string
    {
        return $this->side === 'student' ? 'advisor' : 'student';
    }

    /** آیا thread آماده است (مکالمه وجود دارد و قفل نیست)؟ */
    protected function threadReady(): bool
    {
        return $this->conversation !== null;
    }

    // ─────────────────────────── ارسال ───────────────────────────

    public function send(): void
    {
        if (! $this->threadReady()) {
            return;
        }

        $this->validate([
            'body'  => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB
        ], [
            'body.max'    => 'حداکثر ۵۰۰۰ کاراکتر مجاز است.',
            'image.image' => 'فایل انتخابی باید یک تصویر باشد.',
            'image.mimes' => 'فقط فرمت‌های jpg، png و webp مجاز است.',
            'image.max'   => 'حداکثر حجم تصویر ۲ مگابایت است.',
        ]);

        $body = trim((string) $this->body);

        if ($body === '' && ! $this->image) {
            $this->addError('body', 'پیام نمی‌تواند خالی باشد؛ متن یا تصویر وارد کنید.');
            return;
        }

        $imagePath = $this->image ? $this->storeImageAsWebp($this->image) : null;

        // فقط به پیامی از همین مکالمه اجازه‌ی ریپلای داده می‌شود.
        $replyToId = $this->replyToId
            && $this->conversation->messages()->whereKey($this->replyToId)->exists()
                ? $this->replyToId
                : null;

        $this->conversation->messages()->create([
            'sender_type' => $this->side,
            'body'        => $body !== '' ? strip_tags($body) : null,
            'image_path'  => $imagePath,
            'reply_to_id' => $replyToId,
        ]);

        // به‌روزرسانی متادیتای مکالمه: زمان آخرین پیام + heartbeat + پایان تایپ.
        $this->conversation->forceFill([
            'last_message_at'            => now(),
            "{$this->side}_last_seen_at" => now(),
            "{$this->side}_typing_at"    => null,
        ])->save();

        $this->reset(['body', 'image', 'replyToId']);
        $this->resetValidation();
        $this->dispatch('chat-message-sent'); // برای اسکرول به پایین
    }

    /**
     * مسیریابی دکمه‌ی ارسالِ کامپوزر:
     * اگر در حال ویرایش باشیم → ذخیره‌ی ویرایش، در غیر این صورت → ارسالِ پیام جدید.
     */
    public function submitComposer(): void
    {
        if ($this->editingId) {
            $this->saveEdit();
            return;
        }

        $this->send();
    }

    // ─────────────────────────── ریپلای ───────────────────────────

    public function startReply(int $id): void
    {
        if (! $this->threadReady()) {
            return;
        }

        $message = $this->conversation->messages()
            ->whereKey($id)
            ->whereNull('deleted_at')
            ->first();

        if (! $message) {
            return;
        }

        // ریپلای و ویرایش هم‌زمان معنا ندارند.
        $this->cancelEdit();
        $this->replyToId = $message->id;
    }

    public function cancelReply(): void
    {
        $this->replyToId = null;
    }

    /** تبدیل تصویرِ آپلودی به webp و ذخیره زیر public_html. */
    protected function storeImageAsWebp($image): string
    {
        $manager = extension_loaded('imagick')
            ? new ImageManager(new ImagickDriver())
            : new ImageManager(new Driver());

        $relativeDir = "advisor-chat/{$this->conversation->id}";
        $fullDir = base_path('public_html/' . $relativeDir);
        if (! is_dir($fullDir)) {
            mkdir($fullDir, 0755, true);
        }

        $filename = Str::random(40) . '.webp';
        $encoded = $manager->read($image->getRealPath())
            ->scaleDown(1600, 1600)
            ->toWebp(82);

        file_put_contents($fullDir . '/' . $filename, (string) $encoded);
        @unlink($image->getRealPath());

        return $relativeDir . '/' . $filename;
    }

    // ─────────────────────────── ویرایش ───────────────────────────

    public function startEdit(int $id): void
    {
        $message = $this->ownMessage($id);
        if (! $message || $message->deleted_at !== null) {
            return;
        }

        // ویرایش و ریپلای هم‌زمان معنا ندارند.
        $this->replyToId   = null;
        $this->editingId   = $message->id;
        $this->editingBody = (string) $message->body;
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editingBody' => 'required|string|max:5000',
        ], [
            'editingBody.required' => 'متن پیام نمی‌تواند خالی باشد.',
            'editingBody.max'      => 'حداکثر ۵۰۰۰ کاراکتر مجاز است.',
        ]);

        $message = $this->editingId ? $this->ownMessage($this->editingId) : null;
        if (! $message || $message->deleted_at !== null) {
            $this->cancelEdit();
            return;
        }

        $message->update([
            'body'      => strip_tags(trim($this->editingBody)),
            'edited_at' => now(),
        ]);

        $this->cancelEdit();
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'editingBody']);
        $this->resetValidation();
    }

    // ─────────────────────────── حذف (نرم) ───────────────────────────

    public function deleteMessage(int $id): void
    {
        $message = $this->ownMessage($id);
        if (! $message || $message->deleted_at !== null) {
            return;
        }

        // حذف نرم: ردیف و فایل در دیتابیس/دیسک می‌مانند، فقط tombstone نمایش داده می‌شود.
        $message->update(['deleted_at' => now()]);
    }

    /**
     * حذف گروهی از حالت «انتخاب چندتایی» — «فقط برای خودم».
     * پیام‌ها فقط از نمای همین طرف ({side}_deleted_at) پنهان می‌شوند؛ برای طرف مقابل
     * دست‌نخورده می‌مانند و هیچ «این پیام حذف شد»ی نمایش داده نمی‌شود.
     */
    public function deleteSelected(array $ids): void
    {
        if (! $this->threadReady() || empty($ids)) {
            return;
        }

        $ids = array_filter(array_map('intval', $ids));

        $this->conversation->messages()
            ->whereIn('id', $ids)
            ->whereNull("{$this->side}_deleted_at")
            ->update(["{$this->side}_deleted_at" => now()]);

        $this->dispatch('selection-cleared');
    }

    /** فقط پیامی که متعلق به همین مکالمه و خودِ فرستنده‌ی فعلی (side) است. */
    protected function ownMessage(int $id): ?ConversationMessage
    {
        if (! $this->threadReady()) {
            return null;
        }

        return $this->conversation->messages()
            ->where('id', $id)
            ->where('sender_type', $this->side)
            ->first();
    }

    // ─────────────────────────── زنده (polling) ───────────────────────────

    /** از سمت کلاینت با debounce صدا زده می‌شود تا «در حال تایپ» را اعلام کند. */
    public function setTyping(): void
    {
        if (! $this->threadReady()) {
            return;
        }

        $this->conversation->forceFill([
            "{$this->side}_typing_at" => now(),
        ])->save();
    }

    /**
     * هدف wire:poll — پیام‌های طرف مقابل را خوانده علامت می‌زند و heartbeat می‌زند.
     */
    public function tick(): void
    {
        if (! $this->threadReady()) {
            return;
        }

        $this->markOtherSideRead();
        $this->heartbeat();
        $this->conversation->refresh();
    }

    protected function markOtherSideRead(): void
    {
        $this->conversation->messages()
            ->where('sender_type', $this->otherSide())
            ->whereNull('read_at')
            ->whereNull('deleted_at')
            ->update(['read_at' => now()]);

        $this->conversation->forceFill([
            "{$this->side}_last_read_at" => now(),
        ])->save();
    }

    protected function heartbeat(): void
    {
        $this->conversation->forceFill([
            "{$this->side}_last_seen_at" => now(),
        ])->save();
    }
}
