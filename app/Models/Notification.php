<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Relations\HasMany;


class Notification extends Model

{

    protected $guarded = [];


    protected $casts = [

        'is_read' => 'boolean',

        'is_from_manager' => 'boolean',

        'read_at' => 'datetime',

    ];


    /**
     * دسته‌بندی‌های موجود
     */

    const CATEGORY_ANNOUNCEMENT = 'announcement';

    const CATEGORY_SPECIAL = 'special';

    const CATEGORY_ADVISOR = 'advisor';

    const CATEGORY_SUPPORTER = 'supporter';


    /**
     * نوع گیرنده‌ها
     */

    const TARGET_ALL_USERS = 'all_users';

    const TARGET_ALL_STUDENTS = 'all_students';

    const TARGET_SINGLE = 'single';


    /**
     * دسته‌بندی‌های قابل نمایش برای manager
     */

    public static function managerCategories(): array

    {

        return [

            self::CATEGORY_ANNOUNCEMENT => 'اعلانات',

            self::CATEGORY_SPECIAL => 'اعلان ویژه',

        ];

    }


    /**
     * دسته‌بندی‌های قابل نمایش برای دانش‌آموزان
     */

    public static function studentCategories(): array

    {

        return [

            self::CATEGORY_ANNOUNCEMENT => 'اعلانات',

            self::CATEGORY_SPECIAL => 'اعلان ویژه',

            self::CATEGORY_ADVISOR => 'مشاور',

            self::CATEGORY_SUPPORTER => 'پشتیبان',

        ];

    }


    /**
     * دسته‌بندی‌های قابل نمایش برای کاربران عادی (غیر دانش‌آموز)
     */

    public static function userCategories(): array

    {

        return [

            self::CATEGORY_ANNOUNCEMENT => 'اعلانات',

            self::CATEGORY_SPECIAL => 'اعلان ویژه',

        ];

    }


    /**
     * نوع گیرنده‌های قابل انتخاب برای manager
     */

    public static function targetTypes(): array

    {

        return [

            self::TARGET_ALL_USERS => 'همه کاربران',

            self::TARGET_ALL_STUDENTS => 'همه دانش‌آموزان',

        ];

    }


    /**
     * ادمین فرستنده (مشاور یا پشتیبان)
     */

    public function admin(): BelongsTo

    {

        return $this->belongsTo(Admin::class);

    }


    /**
     * دانش‌آموز گیرنده (برای پیام‌های تکی قدیمی)
     */

    public function student(): BelongsTo

    {

        return $this->belongsTo(Student::class);

    }


    /**
     * گیرندگان این اعلان
     */

    public function recipients(): HasMany

    {

        return $this->hasMany(NotificationRecipient::class);

    }


    /**
     * کاربرانی که این اعلان را دریافت کرده‌اند
     */

    public function users(): BelongsToMany

    {

        return $this->belongsToMany(User::class, 'notification_recipients')
            ->withPivot(['is_read', 'read_at'])
            ->withTimestamps();

    }


    /**
     * نام دسته‌بندی به فارسی
     */

    public function getCategoryLabelAttribute(): string

    {

        $categories = [

            self::CATEGORY_ANNOUNCEMENT => 'اعلانات',

            self::CATEGORY_SPECIAL => 'اعلان ویژه',

            self::CATEGORY_ADVISOR => 'مشاور',

            self::CATEGORY_SUPPORTER => 'پشتیبان',

        ];


        return $categories[$this->category] ?? $this->category;

    }


    /**
     * نام نوع گیرنده به فارسی
     */

    public function getTargetTypeLabelAttribute(): string

    {

        $types = [

            self::TARGET_ALL_USERS => 'همه کاربران',

            self::TARGET_ALL_STUDENTS => 'همه دانش‌آموزان',

            self::TARGET_SINGLE => 'تکی',

        ];


        return $types[$this->target_type] ?? $this->target_type;

    }


    /**
     * تعداد کل گیرندگان
     */

    public function getTotalRecipientsAttribute(): int

    {

        return $this->recipients()->count();

    }


    /**
     * تعداد گیرندگانی که خوانده‌اند
     */

    public function getReadCountAttribute(): int

    {

        return $this->recipients()->where('is_read', true)->count();

    }


    /**
     * تعداد گیرندگانی که نخوانده‌اند
     */

    public function getUnreadCountAttribute(): int

    {

        return $this->recipients()->where('is_read', false)->count();

    }


    /**
     * Scope: اعلان‌های دسته‌بندی خاص
     */

    public function scopeCategory($query, string $category)

    {

        return $query->where('category', $category);

    }


    /**
     * Scope: اعلان‌های از پنل manager
     */

    public function scopeFromManager($query)

    {

        return $query->where('is_from_manager', true);

    }


    /**
     * Scope: اعلان‌های از پنل admin (مشاور/پشتیبان)
     */

    public function scopeFromAdmin($query)

    {

        return $query->where('is_from_manager', false);

    }

}
