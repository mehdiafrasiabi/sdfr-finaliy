<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public static function sendToStudent(?int $studentId, string $title, string $body, ?int $adminId = null): void
    {
        if (!$studentId) {
            return;
        }

        Notification::create([
            'admin_id' => $adminId ?? Auth::id(),
            'student_id' => $studentId,
            'title' => $title,
            'body' => $body,
        ]);
    }
}
