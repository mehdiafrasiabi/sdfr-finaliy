<?php

namespace App\Livewire\Client\Profile\Classification;

use App\Models\CcChapter;

use App\Models\CcField;

use App\Models\CcGrade;

use App\Models\CcSubject;

use App\Models\CcTopic;

use App\Models\ClassificationProject;

use App\Models\PersonalInformation;

use App\Models\ProjectGradeSetting;

use App\Models\StudentClassification;

use App\Models\StudentClassificationSubmission;

use Artesaos\SEOTools\Traits\SEOTools;

use Livewire\Component;


class Classify extends Component

{

    use SEOTools;


    public ClassificationProject $project;

    public $selectedGrade;


    public $studentGrade;

    public $studentField;

    public $studentFieldSlug;


    public $availableTags = [];

    public $activeTag = null;


    public $subjects = [];

    public $ratings = [];


    public $showSubmitModal = false;

    public $showMyTopics = false;


    public $totalTopics = 0;

    public $completedTopics = 0;


    public function mount(ClassificationProject $project, $grade)

    {

        $this->project = $project;

        $this->selectedGrade = (int)$grade;


        $this->loadStudentInfo();

        $this->loadAvailableTags();

        $this->loadExistingRatings();


        $this->seoConfig();

    }


    public function seoConfig()

    {

        $this->seo()->setTitle('طبقه‌بندی مباحث - ' . $this->project->name);

    }


    protected function loadStudentInfo()

    {

        $personalInfo = PersonalInformation::where('user_id', auth()->id())->first();


        if ($personalInfo) {

            $this->studentGrade = (int)$personalInfo->grade;

            $this->studentField = $personalInfo->field;


            // Map to cc_fields slug

            $fieldMapping = [

                'math' => 'math',

                'experimental' => 'experimental',

                'human' => 'human',

            ];

            $this->studentFieldSlug = $fieldMapping[$this->studentField] ?? null;

        }

    }


    protected function loadAvailableTags()

    {

        $settings = $this->project->gradeSettings()
            ->where('student_grade', $this->studentGrade)
            ->get();


        $gradeNames = [10 => 'دهم', 11 => 'یازدهم', 12 => 'دوازدهم'];


        foreach ($settings as $setting) {

            // Add specialized tag

            $this->availableTags[] = [

                'id' => $setting->target_grade . '_specialized',

                'grade' => $setting->target_grade,

                'grade_name' => $gradeNames[$setting->target_grade] ?? $setting->target_grade,

                'type' => 'specialized',

                'type_name' => 'تخصصی',

                'label' => $gradeNames[$setting->target_grade] . ' (تخصصی)',

            ];


            // Add general tag if available

            if ($setting->has_general) {

                $this->availableTags[] = [

                    'id' => $setting->target_grade . '_general',

                    'grade' => $setting->target_grade,

                    'grade_name' => $gradeNames[$setting->target_grade] ?? $setting->target_grade,

                    'type' => 'general',

                    'type_name' => 'عمومی',

                    'label' => $gradeNames[$setting->target_grade] . ' (عمومی)',

                ];

            }

        }


        // Set the tag matching selectedGrade as active, otherwise use first tag

        if (!empty($this->availableTags)) {

            // Find tag matching the selected grade

            $matchingTag = collect($this->availableTags)->first(function($tag) {

                return (int)$tag['grade'] === $this->selectedGrade;

            });



            if ($matchingTag) {

                $this->activeTag = $matchingTag['id'];

            } else {

                // Fallback to first tag if no match found

                $this->activeTag = $this->availableTags[0]['id'];

            }



            $this->loadSubjects();

        }

    }


    protected function loadExistingRatings()

    {

        $existingRatings = StudentClassification::where('user_id', auth()->id())
            ->where('classification_project_id', $this->project->id)
            ->pluck('rating', 'cc_topic_id')
            ->toArray();


        $this->ratings = $existingRatings;

        $this->completedTopics = count($existingRatings);


        // Calculate total topics

        $this->calculateTotalTopics();

    }


    protected function calculateTotalTopics()

    {

        $total = 0;

        $settings = $this->project->gradeSettings()
            ->where('student_grade', $this->studentGrade)
            ->get();


        $studentFieldId = CcField::where('slug', $this->studentFieldSlug)->value('id');


        foreach ($settings as $setting) {

            // Get cc_grade_id for this grade number

            $ccGrade = CcGrade::where('grade_number', $setting->target_grade)->first();


            if (!$ccGrade) continue;


            // Count specialized topics

            $specializedQuery = CcSubject::where('cc_grade_id', $ccGrade->id)
                ->where('type', 'specialized')
                ->where(function ($q) use ($studentFieldId) {

                    $q->where('cc_field_id', $studentFieldId)
                        ->orWhereNull('cc_field_id');

                });


            foreach ($specializedQuery->with('chapters.topics')->get() as $subject) {


                foreach ($subject->chapters as $chapter) {

                    foreach ($chapter->topics->where('is_active', true)->whereNull('parent_id') as $topic) {

                        $total += 1;

                    }
                }

            }


            // Count general topics if available

            if ($setting->has_general) {

                $generalQuery = CcSubject::where('cc_grade_id', $ccGrade->id)
                    ->where('type', 'general');


                foreach ($generalQuery->with('chapters.topics')->get() as $subject) {


                    foreach ($subject->chapters as $chapter) {

                        foreach ($chapter->topics->where('is_active', true)->whereNull('parent_id') as $topic) {


                            $total += 1;

                        }
                    }

                }

            }

        }


        $this->totalTopics = $total;

    }


    public function selectTag($tagId)

    {

        $this->activeTag = $tagId;

        $this->showMyTopics = false;

        $this->loadSubjects();

    }


    public function showMyRatings()

    {

        $this->showMyTopics = true;

        $this->activeTag = null;

    }


    protected function loadSubjects()

    {

        if (!$this->activeTag) {

            $this->subjects = [];

            return;

        }


        $parts = explode('_', $this->activeTag);

        $gradeNumber = (int)$parts[0];

        $type = $parts[1];


        // Get cc_grade_id

        $ccGrade = CcGrade::where('grade_number', $gradeNumber)->first();


        if (!$ccGrade) {

            $this->subjects = [];

            return;

        }


        // Get student's field id

        $studentFieldId = CcField::where('slug', $this->studentFieldSlug)->value('id');


        // Build query

        $query = CcSubject::where('cc_grade_id', $ccGrade->id)
            ->where('type', $type);


        if ($type === 'specialized') {

            $query->where(function ($q) use ($studentFieldId) {

                $q->where('cc_field_id', $studentFieldId)
                    ->orWhereNull('cc_field_id');

            });

        }


        $this->subjects = $query->with(['chapters' => function ($q) {

            $q->active()->ordered()->with(['topics' => function ($q) {

                $q->active()->ordered()->mainTopics()->with(['children' => function ($subQ) {

                    $subQ->active()->ordered();

                }]);

            }]);

        }])->ordered()->get()->toArray();

    }


    public function setRating($topicId, $rating)

    {

        $this->ratings[$topicId] = $rating;


        // Save to database immediately

        StudentClassification::updateOrCreate(

            [

                'user_id' => auth()->id(),

                'classification_project_id' => $this->project->id,

                'cc_topic_id' => $topicId,

            ],

            [

                'rating' => $rating,

            ]

        );


        $this->completedTopics = count($this->ratings);

    }


    public function clearRating($topicId)

    {

        unset($this->ratings[$topicId]);


        StudentClassification::where('user_id', auth()->id())
            ->where('classification_project_id', $this->project->id)
            ->where('cc_topic_id', $topicId)
            ->delete();


        $this->completedTopics = count($this->ratings);

    }


    public function openSubmitModal()
    {

        $this->showSubmitModal = true;

    }


    public function submitClassification()

    {


        // Allow partial submissions - students can submit without completing all topics
        // Validation removed to allow flexible classification


        StudentClassificationSubmission::updateOrCreate(

            [

                'user_id' => auth()->id(),

                'classification_project_id' => $this->project->id,

            ],

            [

                'is_completed' => true,

                'submitted_at' => now(),

            ]

        );


        $this->showSubmitModal = false;

        $this->dispatch('success', 'طبقه‌بندی شما با موفقیت ثبت شد!');


        return redirect()->route('client.profile.classification.projects');

    }

    public function getStarColor($rating)

    {

        return match(true) {

            $rating >= 7 => 'bg-green-500 text-white',

            $rating >= 5 => 'bg-blue-500 text-white',

            $rating >= 3 => 'bg-amber-500 text-white',

            default => 'bg-red-500 text-white',

        };

    }
    public function getRatingLabel($rating)

    {

        $labels = [1 => 'D', 2 => 'D+', 3 => 'C', 4 => 'C+', 5 => 'B', 6 => 'B+', 7 => 'A', 8 => 'A+'];

        return $labels[$rating] ?? '';

    }

    public function getRatingBadgeColor($rating)

    {

        return match(true) {

            $rating >= 7 => 'bg-green-500/20 text-green-500',

            $rating >= 5 => 'bg-blue-500/20 text-blue-500',

            $rating >= 3 => 'bg-amber-500/20 text-amber-500',

            default => 'bg-red-500/20 text-red-500',

        };

    }

    public function render()

    {

        $myRatedTopics = [];


        if ($this->showMyTopics) {

            $myRatedTopics = StudentClassification::where('user_id', auth()->id())
                ->where('classification_project_id', $this->project->id)
                ->with(['topic.chapter.subject.grade', 'topic.parent'])
                ->get()
                ->groupBy(function ($item) {

                    return $item->topic->chapter->subject->name;

                });

        }


        return view('livewire.client.profile.classification.classify', [

            'myRatedTopics' => $myRatedTopics,

        ])->layout('layouts.client.app');

    }

}
