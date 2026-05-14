<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\NotificationRecipient;
use App\Models\Student;
class NotificationService

{

    /**
     * ارسال نوتیفیکیشن به دانش‌آموز با تشخیص خودکار دسته‌بندی بر اساس نقش ادمین
     *
     * @param int|null $studentId شناسه دانش‌آموز
     * @param string $title عنوان پیام
     * @param string $body متن پیام
     * @param int|null $adminId شناسه ادمین (اختیاری، پیش‌فرض: کاربر فعلی)
     * @param string|null $category دسته‌بندی (اختیاری، در صورت عدم ارسال خودکار تشخیص داده می‌شود)
     */

    public static function sendToStudent(

        ?int    $studentId,

        string  $title,

        string  $body,

        ?int    $adminId = null,

        ?string $category = null

    ): void
    {

        if (!$studentId) {

            return;

        }


        $adminId = $adminId ?? Auth::guard('admin')->id();

        $student = Student::with('user')->find($studentId);


        if (!$student || !$student->user) {

            return;

        }


        // تشخیص خودکار دسته‌بندی بر اساس نقش ادمین

        if (!$category) {

            $category = self::detectCategory($adminId, $student);

        }


        // ایجاد نوتیفیکیشن

        $notification = Notification::create([

            'admin_id' => $adminId,

            'student_id' => $studentId,

            'title' => $title,

            'body' => $body,

            'category' => $category,

            'target_type' => Notification::TARGET_SINGLE,

            'is_from_manager' => false,

        ]);


        // ایجاد رکورد گیرنده

        NotificationRecipient::create([

            'notification_id' => $notification->id,

            'user_id' => $student->user->id,

            'is_read' => false,

        ]);

    }


    /**
     * تشخیص خودکار دسته‌بندی بر اساس نقش ادمین نسبت به دانش‌آموز
     */

    protected static function detectCategory(int $adminId, Student $student): string

    {

        $isAdvisor = $student->advisor_id === $adminId;

        if ($isAdvisor) {

            return Notification::CATEGORY_ADVISOR;

        }


        // پیش‌فرض: اعلانات

        return Notification::CATEGORY_ANNOUNCEMENT;

    }


    /**
     * ارسال نوتیفیکیشن با دسته‌بندی مشاور
     */

    public static function sendAsAdvisor(int $studentId, string $title, string $body, ?int $adminId = null): void

    {

        self::sendToStudent($studentId, $title, $body, $adminId, Notification::CATEGORY_ADVISOR);

    }


    /**
     * ارسال نوتیفیکیشن با دسته‌بندی پشتیبان
     */

    public static function sendAsSupporter(int $studentId, string $title, string $body, ?int $adminId = null): void

    {

        self::sendToStudent($studentId, $title, $body, $adminId, Notification::CATEGORY_SUPPORTER);

    }

}
