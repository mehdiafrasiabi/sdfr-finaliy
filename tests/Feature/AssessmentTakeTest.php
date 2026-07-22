<?php

namespace Tests\Feature;

use App\Livewire\Client\Profile\Assessment\AssessmentTake;
use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentQuestionOption;
use App\Models\Student;
use App\Models\StudentAssessmentAttempt;
use App\Models\User;
use App\Services\AssessmentService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class AssessmentTakeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_vark_submit_saves_answers_and_completes_attempt(): void
    {
        Assessment::query()->update(['is_active' => false]);

        $user = $this->createUser();
        $this->createStudentFor($user);
        $assessment = $this->createVarkAssessment('vark-submit-test');
        $nextAssessment = $this->createLikertAssessment('next-after-vark-test');

        $payload = $this->payloadFor($assessment);

        Livewire::actingAs($user)
            ->test(AssessmentTake::class, ['slug' => $assessment->slug])
            ->call('submitAll', $payload)
            ->assertHasNoErrors()
            ->assertRedirect(route('client.profile.assessment.take', ['slug' => $nextAssessment->slug]));

        $attempt = StudentAssessmentAttempt::query()
            ->where('user_id', $user->id)
            ->where('assessment_id', $assessment->id)
            ->firstOrFail();

        $this->assertSame(StudentAssessmentAttempt::STATUS_COMPLETED, $attempt->status);
        $this->assertSame($assessment->questions()->where('is_active', true)->count(), $attempt->answered_count);
        $this->assertSame($attempt->answered_count, $attempt->answers()->count());
        $this->assertIsArray($attempt->computed_result);
        $this->assertNotSame('', $attempt->computed_result['profile'] ?? '');
    }

    public function test_assessment_list_continue_redirects_to_next_unfinished_assessment(): void
    {
        Assessment::query()->update(['is_active' => false]);

        $user = $this->createUser();
        $this->createStudentFor($user);
        $vark = $this->createVarkAssessment('vark-list-start-test');
        $nextAssessment = $this->createLikertAssessment('next-from-list-test');
        $service = app(AssessmentService::class);

        $attempt = $service->startOrResume($user, $vark);
        foreach ($vark->questions()->with('options')->get() as $question) {
            $service->saveAnswer($attempt, $question, [
                'selected_option_id' => null,
                'selected_options' => [$question->options->first()->id],
                'free_value' => null,
            ]);
        }
        $service->complete($attempt);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Client\Profile\Assessment\AssessmentList::class)
            ->assertSee(route('client.profile.assessment.take', ['slug' => $nextAssessment->slug]), false)
            ->call('start')
            ->assertRedirect(route('client.profile.assessment.take', ['slug' => $nextAssessment->slug]));
    }

    public function test_completed_assessment_take_redirects_directly_to_next_unfinished_assessment(): void
    {
        Assessment::query()->update(['is_active' => false]);

        $user = $this->createUser();
        $this->createStudentFor($user);
        $vark = $this->createVarkAssessment('vark-completed-take-test');
        $nextAssessment = $this->createLikertAssessment('next-from-completed-take-test');
        $service = app(AssessmentService::class);

        $attempt = $service->startOrResume($user, $vark);
        foreach ($vark->questions()->with('options')->get() as $question) {
            $service->saveAnswer($attempt, $question, [
                'selected_option_id' => null,
                'selected_options' => [$question->options->first()->id],
                'free_value' => null,
            ]);
        }
        $service->complete($attempt);

        Livewire::actingAs($user)
            ->test(AssessmentTake::class, ['slug' => $vark->slug])
            ->assertRedirect(route('client.profile.assessment.take', ['slug' => $nextAssessment->slug]));
    }

    public function test_http_completed_assessment_take_redirects_once_to_next_unfinished_assessment(): void
    {
        Assessment::query()->update(['is_active' => false]);

        $user = $this->createUser();
        $this->createStudentFor($user);
        $vark = $this->createVarkAssessment('vark-http-redirect-test');
        $nextAssessment = $this->createLikertAssessment('next-http-redirect-test');

        $this->completeVarkFor($user, $vark);

        $this->actingAs($user)
            ->get(route('client.profile.assessment.take', ['slug' => $vark->slug]))
            ->assertRedirect(route('client.profile.assessment.take', ['slug' => $nextAssessment->slug]));
    }

    public function test_http_assessment_list_for_started_flow_returns_page_with_next_link_not_redirect_loop(): void
    {
        Assessment::query()->update(['is_active' => false]);

        $user = $this->createUser();
        $this->createStudentFor($user);
        $vark = $this->createVarkAssessment('vark-http-list-test');
        $nextAssessment = $this->createLikertAssessment('next-http-list-test');

        $this->completeVarkFor($user, $vark);

        $this->actingAs($user)
            ->get(route('client.profile.assessment.list'))
            ->assertOk()
            ->assertSee(route('client.profile.assessment.take', ['slug' => $nextAssessment->slug]), false);
    }

    public function test_start_or_resume_does_not_reset_completed_vark_attempt(): void
    {
        Assessment::query()->update(['is_active' => false]);

        $user = $this->createUser();
        $this->createStudentFor($user);
        $assessment = $this->createVarkAssessment('vark-resume-test');
        $service = app(AssessmentService::class);

        $attempt = $service->startOrResume($user, $assessment);
        foreach ($assessment->questions()->with('options')->get() as $question) {
            $service->saveAnswer($attempt, $question, [
                'selected_option_id' => null,
                'selected_options' => [$question->options->first()->id],
                'free_value' => null,
            ]);
        }
        $service->complete($attempt);

        $completedAttempt = $attempt->refresh();
        $this->assertSame(StudentAssessmentAttempt::STATUS_COMPLETED, $completedAttempt->status);
        $this->assertSame(2, $completedAttempt->answers()->count());

        $resumed = $service->startOrResume($user, $assessment)->refresh();

        $this->assertTrue($resumed->isCompleted());
        $this->assertSame($completedAttempt->id, $resumed->id);
        $this->assertSame(2, $resumed->answers()->count());
        $this->assertNotNull($resumed->computed_result);

        Livewire::actingAs($user)
            ->test(AssessmentTake::class, ['slug' => $assessment->slug])
            ->assertRedirect(route('client.profile.assessment.list'));

        $this->assertSame(2, $resumed->refresh()->answers()->count());
        $this->assertTrue($resumed->isCompleted());
    }

    private function createVarkAssessment(string $slug): Assessment
    {
        $assessment = Assessment::query()->create([
            'slug' => $slug,
            'name_fa' => 'VARK test',
            'kind' => Assessment::KIND_VARK,
            'question_type' => AssessmentQuestion::TYPE_VARK_MULTI,
            'is_active' => true,
            'is_required' => true,
            'display_order' => 1,
            'audience' => Assessment::AUDIENCE_STUDENT,
            'expected_question_count' => 2,
            'interpretation' => ['engine' => 'modality'],
        ]);

        foreach ([1, 2] as $order) {
            $question = AssessmentQuestion::query()->create([
                'assessment_id' => $assessment->id,
                'order' => $order,
                'question_text_fa' => 'Question ' . $order,
                'type' => AssessmentQuestion::TYPE_VARK_MULTI,
                'is_active' => true,
            ]);

            foreach (['V', 'A', 'R', 'K'] as $index => $modality) {
                AssessmentQuestionOption::query()->create([
                    'question_id' => $question->id,
                    'order' => $index + 1,
                    'label_fa' => $modality,
                    'value' => $modality,
                    'weights' => [$modality => 1],
                ]);
            }
        }

        return $assessment->refresh();
    }

    private function createLikertAssessment(string $slug): Assessment
    {
        $assessment = Assessment::query()->create([
            'slug' => $slug,
            'name_fa' => 'Likert test',
            'kind' => Assessment::KIND_CUSTOM,
            'question_type' => 'mixed',
            'is_active' => true,
            'is_required' => true,
            'display_order' => 2,
            'audience' => Assessment::AUDIENCE_STUDENT,
            'expected_question_count' => 1,
            'interpretation' => ['engine' => 'facet'],
        ]);

        $question = AssessmentQuestion::query()->create([
            'assessment_id' => $assessment->id,
            'order' => 1,
            'question_text_fa' => 'Likert question',
            'type' => AssessmentQuestion::TYPE_LIKERT5,
            'scoring_meta' => ['facet' => 'focus'],
            'is_active' => true,
        ]);

        foreach ([1, 2, 3, 4, 5] as $value) {
            AssessmentQuestionOption::query()->create([
                'question_id' => $question->id,
                'order' => $value,
                'label_fa' => (string) $value,
                'value' => (string) $value,
                'weights' => [],
            ]);
        }

        return $assessment->refresh();
    }

    private function completeVarkFor(User $user, Assessment $vark): StudentAssessmentAttempt
    {
        $service = app(AssessmentService::class);
        $attempt = $service->startOrResume($user, $vark);

        foreach ($vark->questions()->with('options')->get() as $question) {
            $service->saveAnswer($attempt, $question, [
                'selected_option_id' => null,
                'selected_options' => [$question->options->first()->id],
                'free_value' => null,
            ]);
        }

        $service->complete($attempt);

        return $attempt->refresh();
    }

    private function createUser(): User
    {
        return User::query()->create([
            'name' => 'Assessment Tester',
            'email' => uniqid('assessment-test-', true) . '@example.test',
            'mobile' => '09' . random_int(100000000, 999999999),
            'password' => 'password',
        ]);
    }

    private function createStudentFor(User $user): Student
    {
        return Student::query()->create([
            'user_id' => $user->id,
            'advisor_id' => null,
            'payment_id' => null,
            'star' => 'D',
            'is_trial' => true,
        ]);
    }

    private function payloadFor(Assessment $assessment): array
    {
        return $assessment
            ->questions()
            ->with('options')
            ->get()
            ->mapWithKeys(fn (AssessmentQuestion $question) => [
                (string) $question->id => [(string) $question->options->first()->id],
            ])
            ->all();
    }
}
