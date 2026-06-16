<?php

namespace App\Support;

use App\Models\CcChapter;
use App\Models\CcSubject;
use App\Models\ClassificationProject;
use App\Models\StudentClassification;

/**
 * تحلیل پیشرفت/پسرفت دانش‌آموزان بر اساس طبقه‌بندی دروس (StudentClassification).
 *
 * طبقه‌بندی ممکن است در سطح «درس» (CcSubject) یا «فصل» (CcChapter) ثبت شده باشد؛
 * این کلاس همه را به سطح «درس» تجمیع می‌کند (میانگین رتبهٔ فصل‌ها) تا روند هر درس
 * در طول پروژه‌های طبقه‌بندی (مرتب بر اساس تاریخ) قابل مقایسه باشد.
 *
 * رتبه: 1=D (ضعیف‌ترین) … 4=A (قوی‌ترین).
 */
class ClassificationProgress
{
    public const WEAK_THRESHOLD = 2; // رتبهٔ ≤ ۲ یعنی ضعیف (C یا D)

    /**
     * نقشهٔ رتبه‌های درس به‌ازای هر دانش‌آموز و هر پروژه.
     *
     * @param  array  $userIds  شناسهٔ کاربرانِ دانش‌آموزان
     * @return array{projects:\Illuminate\Support\Collection, subjects:\Illuminate\Support\Collection, data:array<int,array<int,array<int,float>>>}
     *         data[userId][subjectId][projectId] = میانگین رتبهٔ آن درس در آن پروژه
     */
    public static function subjectRatings(array $userIds): array
    {
        $empty = ['projects' => collect(), 'subjects' => collect(), 'data' => []];

        if (empty($userIds)) {
            return $empty;
        }

        $rows = StudentClassification::whereIn('user_id', $userIds)
            ->get(['user_id', 'classification_project_id', 'ratable_type', 'ratable_id', 'rating']);

        if ($rows->isEmpty()) {
            return $empty;
        }

        // فصل → درسِ والد
        $chapterIds = $rows->where('ratable_type', CcChapter::class)->pluck('ratable_id')->unique();
        $chapterSubject = $chapterIds->isNotEmpty()
            ? CcChapter::whereIn('id', $chapterIds)->pluck('cc_subject_id', 'id')
            : collect();

        $acc = [];          // [user][subject][project] => ['sum'=>, 'count'=>]
        $subjectIds = [];
        $projectIds = [];

        foreach ($rows as $r) {
            $subjectId = $r->ratable_type === CcChapter::class
                ? ($chapterSubject[$r->ratable_id] ?? null)
                : (int) $r->ratable_id;

            if (!$subjectId) {
                continue;
            }

            $subjectIds[$subjectId] = true;
            $projectIds[$r->classification_project_id] = true;

            $cur = $acc[$r->user_id][$subjectId][$r->classification_project_id] ?? ['sum' => 0, 'count' => 0];
            $cur['sum']   += (int) $r->rating;
            $cur['count'] += 1;
            $acc[$r->user_id][$subjectId][$r->classification_project_id] = $cur;
        }

        $subjects = CcSubject::whereIn('id', array_keys($subjectIds))->pluck('name', 'id');
        $projects = ClassificationProject::whereIn('id', array_keys($projectIds))
            ->orderBy('start_at')
            ->get(['id', 'name', 'start_at']);

        $data = [];
        foreach ($acc as $uid => $subs) {
            foreach ($subs as $sid => $projs) {
                $series = [];
                foreach ($projects as $p) {
                    if (isset($projs[$p->id])) {
                        $series[$p->id] = round($projs[$p->id]['sum'] / max($projs[$p->id]['count'], 1), 2);
                    }
                }
                if (!empty($series)) {
                    $data[$uid][$sid] = $series;
                }
            }
        }

        return ['projects' => $projects, 'subjects' => $subjects, 'data' => $data];
    }

    /** آخرین و یکی‌مانده‌به‌آخرِ سری (به‌ترتیب پروژه) برای محاسبهٔ دلتا. */
    public static function latestPrevious(array $series): array
    {
        $values = array_values($series); // ترتیب پروژه از قبل حفظ شده
        $latest = $values ? end($values) : null;
        $previous = count($values) >= 2 ? $values[count($values) - 2] : null;
        $delta = ($latest !== null && $previous !== null) ? round($latest - $previous, 2) : null;

        return ['latest' => $latest, 'previous' => $previous, 'delta' => $delta];
    }

    /**
     * روند طبقه‌بندیِ هر درسِ یک دانش‌آموز (برای صفحهٔ پیشرفت — M5).
     *
     * @return array<int, array{subject:string, latest:?float, previous:?float, delta:?float}>
     */
    public static function studentSubjectTrends(int $userId): array
    {
        $res = self::subjectRatings([$userId]);
        $subjects = $res['subjects'];
        $data = $res['data'][$userId] ?? [];

        $out = [];
        foreach ($data as $subjectId => $series) {
            $lp = self::latestPrevious($series);
            $out[] = [
                'subject'  => $subjects[$subjectId] ?? '—',
                'latest'   => $lp['latest'],
                'previous' => $lp['previous'],
                'delta'    => $lp['delta'],
            ];
        }

        // ابتدا بیشترین پسرفت، سپس بر اساس نام
        usort($out, fn($a, $b) => ($a['delta'] ?? 0) <=> ($b['delta'] ?? 0));

        return $out;
    }

    /**
     * دروسی که دانش‌آموزانِ یک گروه در آن‌ها ضعیف‌اند (هشدار مدیر — M6).
     * آخرین رتبهٔ هر دانش‌آموز در هر درس مبنا است.
     *
     * @return array<int, array{subject:string, weak:int, total:int, percent:float, avg:float}>
     */
    public static function weakSubjects(array $userIds): array
    {
        $res = self::subjectRatings($userIds);
        $subjects = $res['subjects'];

        $agg = []; // subjectId => ['weak'=>, 'total'=>, 'sum'=>]
        foreach ($res['data'] as $subs) {
            foreach ($subs as $subjectId => $series) {
                $latest = self::latestPrevious($series)['latest'];
                if ($latest === null) {
                    continue;
                }
                $cur = $agg[$subjectId] ?? ['weak' => 0, 'total' => 0, 'sum' => 0];
                $cur['total'] += 1;
                $cur['sum']   += $latest;
                if ($latest <= self::WEAK_THRESHOLD) {
                    $cur['weak'] += 1;
                }
                $agg[$subjectId] = $cur;
            }
        }

        $out = [];
        foreach ($agg as $subjectId => $a) {
            if ($a['weak'] === 0) {
                continue;
            }
            $out[] = [
                'subject' => $subjects[$subjectId] ?? '—',
                'weak'    => $a['weak'],
                'total'   => $a['total'],
                'percent' => round($a['weak'] / max($a['total'], 1) * 100, 1),
                'avg'     => round($a['sum'] / max($a['total'], 1), 2),
            ];
        }

        usort($out, fn($a, $b) => $b['percent'] <=> $a['percent']);

        return $out;
    }

    /**
     * دروسی که دانش‌آموزانِ گروه بیشترین پیشرفت را در آن‌ها داشته‌اند (M7).
     * میانگین دلتای (آخرین − قبلی) رتبهٔ هر درس بین دانش‌آموزان.
     *
     * @return array<int, array{subject:string, avg_delta:float, improved:int, total:int}>
     */
    public static function topProgressSubjects(array $userIds): array
    {
        $res = self::subjectRatings($userIds);
        $subjects = $res['subjects'];

        $agg = []; // subjectId => ['sum'=>, 'count'=>, 'improved'=>]
        foreach ($res['data'] as $subs) {
            foreach ($subs as $subjectId => $series) {
                $delta = self::latestPrevious($series)['delta'];
                if ($delta === null) {
                    continue;
                }
                $cur = $agg[$subjectId] ?? ['sum' => 0, 'count' => 0, 'improved' => 0];
                $cur['sum']   += $delta;
                $cur['count'] += 1;
                if ($delta > 0) {
                    $cur['improved'] += 1;
                }
                $agg[$subjectId] = $cur;
            }
        }

        $out = [];
        foreach ($agg as $subjectId => $a) {
            $avgDelta = round($a['sum'] / max($a['count'], 1), 2);
            $out[] = [
                'subject'   => $subjects[$subjectId] ?? '—',
                'avg_delta' => $avgDelta,
                'improved'  => $a['improved'],
                'total'     => $a['count'],
            ];
        }

        // بیشترین پیشرفت اول
        usort($out, fn($a, $b) => $b['avg_delta'] <=> $a['avg_delta']);

        return $out;
    }

    /** برچسب حرفیِ یک رتبهٔ (احتمالاً اعشاری) با گرد کردن به نزدیک‌ترین سطح. */
    public static function ratingLabel(?float $rating): string
    {
        if ($rating === null) {
            return '—';
        }
        $rounded = (int) round($rating);
        return StudentClassification::RATINGS[$rounded] ?? '—';
    }
}
