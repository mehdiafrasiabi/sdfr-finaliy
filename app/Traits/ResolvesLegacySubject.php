<?php

namespace App\Traits;

use App\Models\CcSubject;
use App\Models\Subject;

/**
 * نگاشت درس‌های جدید (cc_subjects) به درس‌های قدیمی (subjects) که هنوز
 * برای ارتباط با بخش‌های دیگر پروژه (مثلا آزمون‌ها) استفاده می‌شوند.
 *
 * این trait از QuestionForm استخراج شده تا هم در فرم ساخت/ویرایش سوال و هم
 * در ابزار «اصلاحات سوالات» (لینک‌کردن یک سوال موجود به یک مقصد دوم) استفاده شود.
 */
trait ResolvesLegacySubject
{
    protected function resolveLegacySubjectId(int $ccSubjectId): int
    {
        $ccSubject = CcSubject::find($ccSubjectId);

        if (!$ccSubject) {
            throw new \RuntimeException('درس انتخاب‌شده پیدا نشد.');
        }

        $legacyName = $this->resolveLegacySubjectName($ccSubject->name);

        return (int) Subject::firstOrCreate(
            ['name' => $legacyName],
            ['is_active' => true]
        )->id;
    }

    protected function resolveLegacySubjectName(string $ccSubjectName): string
    {
        $name = str_replace(["\u{200C}", "\u{200D}"], '', $ccSubjectName);
        $name = trim(preg_replace('/\d+$/u', '', $name));
        $name = preg_replace('/\s+/u', ' ', $name) ?: $ccSubjectName;

        $map = [
            'دین و زندگی' => 'دین و زندگی',
            'دینی' => 'دین و زندگی',
            'زیست' => 'زیست‌شناسی',
            'فارسی' => 'ادبیات فارسی',
            'زبان انگلیسی' => 'زبان انگلیسی',
            'زبان' => 'زبان انگلیسی',
            'حسابان' => 'حسابان',
            'هندسه' => 'هندسه',
            'گسسته' => 'گسسته',
            'فیزیک' => 'فیزیک',
            'شیمی' => 'شیمی',
            'ریاضی و آمار' => 'آمار و احتمال',
            'آمار' => 'آمار و احتمال',
            'ریاضی' => 'ریاضی',
            'فلسفه' => 'فلسفه و منطق',
            'منطق' => 'فلسفه و منطق',
            'جامعه' => 'جامعه‌شناسی',
            'روانشناسی' => 'روانشناسی',
            'تاریخ' => 'تاریخ',
            'جغرافیا' => 'جغرافیا',
            'اقتصاد' => 'اقتصاد',
            'سلامت' => 'سلامت و بهداشت',
            'کارگاه کارآفرینی' => 'اقتصاد',
            'تفکر و سواد رسانه' => 'علوم اجتماعی',
            'مدیریت خانواده' => 'علوم اجتماعی',
            'آمادگی دفاعی' => 'علوم اجتماعی',
            'انسان و محیط زیست' => 'علوم اجتماعی',
            'علوم و فنون ادبی' => 'ادبیات فارسی',
        ];

        foreach ($map as $needle => $legacyName) {
            if (str_contains($name, $needle)) {
                return $legacyName;
            }
        }

        return $name;
    }
}
