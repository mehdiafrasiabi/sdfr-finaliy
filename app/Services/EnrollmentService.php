<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AdvisingPreSession;
use App\Models\AdvisingSession;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    public function assignSupporter(Enrollment $enrollment, Admin $supporter): void
    {
        DB::transaction(function () use ($enrollment, $supporter) {
            $enrollment->student()->update(['supporter_id' => $supporter->id]);

            $session = AdvisingSession::create([
                'student_id'      => $enrollment->student_id,
                'advisor_id'      => null,
                'title'           => 'جلسه آغاز دوره',
                'activation_date' => Carbon::now()->addDay(),
                'status'          => AdvisingSession::STATUS_ACTIVE,
                'location_type'   => 'online',
                'is_active'       => true,
            ]);

            AdvisingPreSession::create([
                'advising_session_id' => $session->id,
                'student_id'          => $enrollment->student_id,
                'title'               => 'پیش‌جلسه آغاز دوره',
                'status'              => AdvisingPreSession::STATUS_PENDING,
            ]);

            $enrollment->update([
                'supporter_id'          => $supporter->id,
                'supporter_assigned_at' => Carbon::now(),
            ]);
        });
    }
}
