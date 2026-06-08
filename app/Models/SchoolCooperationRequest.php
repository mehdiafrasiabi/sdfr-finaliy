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

    /** حق مدیر (سود/تخفیف) به ازای هر دانش‌آموز، در صورتی که تعداد بالای ۱۰ نفر باشد. */
    public const MANAGER_FEE_PER_STUDENT = 1_000_000;

    /** حداقل تعداد دانش‌آموز برای تعلق گرفتن تخفیف. */
    public const DISCOUNT_THRESHOLD = 10;

    /**
     * محاسبه‌ی هزینه، تخفیف و پک بر اساس تعداد دانش‌آموز.
     *
     * @return array{student_count:int, package:string, payable:int, discount:int, base_price:int}
     */
    public static function calculate(int $count): array
    {
        $count = max(0, $count);

        // هزینه‌ی پلکانی تجمعی (درآمد) دقیقاً مطابق اکسل
        $payable   = 0;
        $remaining = $count;
        foreach (self::TIERS as $i => $tier) {
            $prevMax  = $i === 0 ? 0 : self::TIERS[$i - 1]['max'];
            $capacity = $tier['max'] - $prevMax;
            $take     = min($remaining, $capacity);
            $payable += $take * $tier['price'];
            $remaining -= $take;
            if ($remaining <= 0) {
                break;
            }
        }

        // تخفیف / سود مدیر: بالای ۱۰ نفر، به ازای هر نفر ۱ میلیون تومان
        $discount = $count > self::DISCOUNT_THRESHOLD
            ? $count * self::MANAGER_FEE_PER_STUDENT
            : 0;

        return [
            'student_count' => $count,
            'package'       => self::packageFor($count),
            'payable'       => $payable,
            'discount'      => $discount,
            'base_price'    => $payable + $discount,
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
