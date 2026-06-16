<?php

namespace App\Http\Middleware;

use App\Models\AdvisingSession;
use App\Models\ProgramPart;
use App\Models\WeeklyProgram;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockProfileDuringActiveStudy
{
    /**
     * مسیرهایی که هنگام فعال بودن تایمر مطالعه مجاز هستند.
     * صفحهٔ برنامهٔ هفتگی همان جایی است که تایمر و ثبت ساعت مطالعه در آن انجام می‌شود،
     * بنابراین باید در حین تایمرِ فعال در دسترس بماند.
     */
    private const ALLOWED_ROUTES = [
        'client.profile.consultation.weekly-program',
        'client.logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $currentRoute = $request->route()?->getName();
        if ($currentRoute && in_array($currentRoute, self::ALLOWED_ROUTES, true)) {
            return $next($request);
        }

        if ($this->hasActiveTimer()) {
            $studyRoute = $this->activeStudyRoute();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'message'  => 'یک جلسه مطالعه فعال دارید. ابتدا آن را ثبت یا لغو کنید.',
                    'redirect' => $studyRoute,
                ], 423);
            }

            return redirect($studyRoute)
                ->with('warning', 'یک جلسه مطالعه فعال دارید. ابتدا آن را ثبت یا لغو کنید.');
        }

        return $next($request);
    }

    private function hasActiveTimer(): bool
    {
        $timer = session('active_timer_state');
        if (is_array($timer) && !empty($timer['currentPartId'])) {
            return true;
        }

        $makeup = session('active_makeup_timer_state');
        if (is_array($makeup) && !empty($makeup['topicId'])) {
            return true;
        }

        return false;
    }

    /**
     * مسیر صفحه‌ای که کاربر باید هنگام تایمر فعال به آن هدایت شود:
     * صفحهٔ برنامهٔ هفتگیِ مربوط به همان مطالعه. اگر برنامه قابل تشخیص نبود،
     * به داشبورد برمی‌گردیم تا خطای مسیر رخ ندهد.
     */
    private function activeStudyRoute(): string
    {
        $programId = $this->resolveActiveProgramId();

        if ($programId) {
            return route('client.profile.consultation.weekly-program', $programId);
        }

        return route('client.profile.dashboard');
    }

    private function resolveActiveProgramId(): ?int
    {
        // ۱) اگر تایمرِ یک پارت برنامه فعال است، شناسهٔ برنامه را از همان پارت می‌گیریم.
        $timer = session('active_timer_state');
        if (is_array($timer) && !empty($timer['currentPartId'])) {
            $part = ProgramPart::find($timer['currentPartId']);
            if ($part?->weekly_program_id) {
                return (int) $part->weekly_program_id;
            }
        }

        // ۲) در غیر این صورت (مثلاً تایمر جبرانی)، آخرین برنامهٔ فعال دانش‌آموز را پیدا می‌کنیم.
        $student = auth()->user()?->student;
        if (!$student) {
            return null;
        }

        $latestSession = AdvisingSession::where('student_id', $student->id)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->orderByDesc('activation_date')
            ->orderByDesc('session_time')
            ->first();

        if (!$latestSession) {
            return null;
        }

        $program = WeeklyProgram::where('advising_session_id', $latestSession->id)
            ->where('student_id', $student->id)
            ->first();

        return $program?->id;
    }
}
