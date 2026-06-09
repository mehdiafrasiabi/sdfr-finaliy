<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolCooperationRequest extends Model
{
    protected $guarded = [];

    protected $casts = [
        'student_count'   => 'integer',
        'payable_amount'  => 'integer',
        'discount_amount' => 'integer',
        'is_reviewed'     => 'boolean',
    ];

    /**
     * قیمت پلکانی هر دانش‌آموز بر اساس اکسل قیمت‌گذاری.
     * هر ردیف: تا چند نفر، قیمت هر نفر (تومان)، عنوان پک.
     */
    public const TIERS = [
        ['max' => 10,            'price' => 9_900_000, 'label' => 'برنزی'],
        ['max' => 20,            'price' => 8_850_000, 'label' => 'نقره‌ای'],
        ['max' => 40,            'price' => 7_800_000, 'label' => 'طلایی'],
        ['max' => PHP_INT_MAX,   'price' => 6_750_000, 'label' => 'پلاتینی'],
    ];

    /** قیمت پایه‌ی هر دانش‌آموز (پک برنزی)؛ مبنای محاسبه‌ی تخفیف پلکانی. */
    public const BASE_PRICE_PER_STUDENT = 9_900_000;

    /**
     * قیمت هر دانش‌آموز بر اساس تعداد کل دانش‌آموزان (قیمت‌گذاری مسطح بر اساس پک).
     * هرچه تعداد بیشتر شود، تمام دانش‌آموزان مشمول قیمت ارزان‌تر آن پک می‌شوند.
     */
    public static function pricePerStudent(int $count): int
    {
        foreach (self::TIERS as $tier) {
            if ($count <= $tier['max']) {
                return $tier['price'];
            }
        }
        return self::TIERS[array_key_last(self::TIERS)]['price'];
    }

    /**
     * محاسبه‌ی هزینه، تخفیف و پک بر اساس تعداد دانش‌آموز.
     *
     * @return array{student_count:int, package:string, unit_price:int, payable:int, discount_per_student:int, discount:int, base_price:int}
     */
    public static function calculate(int $count): array
    {
        $count = max(0, $count);

        // قیمت هر نفر بر اساس پک، و هزینه‌ی قابل پرداخت
        $unitPrice = self::pricePerStudent($count);
        $payable   = $count * $unitPrice;

        // تخفیف: کاهش قیمت هر نفر نسبت به قیمت پایه (پک برنزی) × تعداد
        $discountPerStudent = self::BASE_PRICE_PER_STUDENT - $unitPrice;
        $discount           = $count * $discountPerStudent;

        return [
            'student_count'        => $count,
            'package'              => self::packageFor($count),
            'unit_price'           => $unitPrice,
            'payable'              => $payable,
            'discount_per_student' => $discountPerStudent,
            'discount'             => $discount,
            'base_price'           => $count * self::BASE_PRICE_PER_STUDENT,
        ];
    }

    /** عنوان پک بر اساس تعداد کل دانش‌آموزان. */
    public static function packageFor(int $count): string
    {
        foreach (self::TIERS as $tier) {
            if ($count <= $tier['max']) {
                return $tier['label'];
            }
        }
        return self::TIERS[array_key_last(self::TIERS)]['label'];
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
