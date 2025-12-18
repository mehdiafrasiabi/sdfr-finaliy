<?php


namespace Database\Seeders;


use App\Models\Question;

use App\Models\QuestionContent;

use App\Models\QuestionOption;

use App\Models\Subject;

use App\Models\TypedExam;

use App\Models\TypedExamQuestion;

use App\Models\TypedExamSetting;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;


class TypedExamSeeder extends Seeder

{

    /**
     * Run the database seeds.
     */

    public function run(): void

    {

        // First run SubjectSeeder if subjects don't exist

        if (Subject::count() === 0) {

            $this->call(SubjectSeeder::class);

        }


        $this->createQuestions();

        $this->createExams();

    }


    protected function createQuestions(): void

    {

        $subjects = Subject::all();

        $difficulties = ['easy', 'medium', 'hard', 'special'];


        // Sample questions for each subject

        $questionBank = $this->getQuestionBank();


        $questionCount = 0;

        $targetCount = 200;


        foreach ($questionBank as $subjectSlug => $questions) {

            $subject = $subjects->firstWhere('slug', $subjectSlug);

            if (!$subject) continue;


            foreach ($questions as $q) {

                if ($questionCount >= $targetCount) break 2;


                DB::transaction(function () use ($subject, $q, $difficulties) {

                    $question = Question::create([

                        'code' => Question::generateUniqueCode(),

                        'subject_id' => $subject->id,

                        'difficulty' => $q['difficulty'] ?? $difficulties[array_rand($difficulties)],

                        'direction' => 'rtl',

                    ]);


                    QuestionContent::create([

                        'question_id' => $question->id,

                        'body' => $q['body'],

                        'explanation' => $q['explanation'] ?? null,

                    ]);


                    foreach ($q['options'] as $index => $optionText) {

                        QuestionOption::create([

                            'question_id' => $question->id,

                            'option_number' => $index + 1,

                            'content' => $optionText,

                            'is_correct' => ($index + 1) === $q['correct'],

                        ]);

                    }

                });


                $questionCount++;

            }

        }


        $this->command->info("✅ {$questionCount} سوال ایجاد شد.");

    }


    protected function createExams(): void

    {

        $exams = [

            [

                'title' => 'آزمون جامع ریاضی - سطح مقدماتی',

                'academic_year' => '1404-1405',

                'difficulty' => 'easy',

                'description' => 'این آزمون شامل سوالات پایه ریاضی برای سنجش مفاهیم اولیه است.',

                'question_count' => 20,

                'subject_slug' => 'mathematics',

            ],

            [

                'title' => 'آزمون فیزیک - مکانیک',

                'academic_year' => '1404-1405',

                'difficulty' => 'medium',

                'description' => 'آزمون مکانیک شامل مباحث حرکت، نیرو و انرژی.',

                'question_count' => 25,

                'subject_slug' => 'physics',

            ],

            [

                'title' => 'آزمون شیمی - ساختار اتم',

                'academic_year' => '1404-1405',

                'difficulty' => 'medium',

                'description' => 'آزمون شیمی عمومی با تمرکز بر ساختار اتمی و پیوندها.',

                'question_count' => 20,

                'subject_slug' => 'chemistry',

            ],

            [

                'title' => 'آزمون جامع علوم تجربی',

                'academic_year' => '1404-1405',

                'difficulty' => 'comprehensive',

                'description' => 'آزمون ترکیبی از فیزیک، شیمی و زیست‌شناسی.',

                'question_count' => 40,

                'subject_slug' => null, // Mixed

            ],

            [

                'title' => 'آزمون ادبیات فارسی - آرایه‌های ادبی',

                'academic_year' => '1405-1406',

                'difficulty' => 'hard',

                'description' => 'آزمون پیشرفته آرایه‌های ادبی و دستور زبان فارسی.',

                'question_count' => 30,

                'subject_slug' => 'persian-literature',

            ],

            [

                'title' => 'آزمون زبان انگلیسی - گرامر و واژگان',

                'academic_year' => '1405-1406',

                'difficulty' => 'medium',

                'description' => 'آزمون گرامر، واژگان و درک مطلب انگلیسی.',

                'question_count' => 25,

                'subject_slug' => 'english',

            ],

        ];


        foreach ($exams as $examData) {

            DB::transaction(function () use ($examData) {

                $exam = TypedExam::create([

                    'title' => $examData['title'],

                    'academic_year' => $examData['academic_year'],

                    'difficulty' => $examData['difficulty'],

                    'is_random_selection' => false,

                    'is_published' => true,

                ]);


                TypedExamSetting::create([

                    'typed_exam_id' => $exam->id,

                    'result_visibility' => 'immediately',

                    'answer_key_visibility' => 'immediately',

                    'randomization_type' => 'both',

                    'description' => $examData['description'],

                ]);


                // Add questions to exam

                if ($examData['subject_slug']) {

                    $subject = Subject::where('slug', $examData['subject_slug'])->first();

                    $questions = Question::where('subject_id', $subject?->id)
                        ->inRandomOrder()
                        ->limit($examData['question_count'])
                        ->get();

                } else {

                    $questions = Question::inRandomOrder()
                        ->limit($examData['question_count'])
                        ->get();

                }


                $order = 1;

                foreach ($questions as $question) {

                    TypedExamQuestion::create([

                        'typed_exam_id' => $exam->id,

                        'question_id' => $question->id,

                        'order' => $order++,

                    ]);

                }


                $this->command->info("✅ آزمون '{$examData['title']}' با {$questions->count()} سوال ایجاد شد.");

            });

        }

    }


    protected function getQuestionBank(): array

    {

        return [

            'mathematics' => [

                [

                    'body' => '<p>حاصل عبارت $2 + 2 × 3$ کدام است؟</p>',

                    'options' => ['۶', '۸', '۱۲', '۱۰'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                    'explanation' => '<p>طبق اولویت عملیات، ابتدا ضرب انجام می‌شود: $2 × 3 = 6$، سپس جمع: $2 + 6 = 8$</p>',

                ],

                [

                    'body' => '<p>اگر $x + 5 = 12$ باشد، مقدار $x$ چقدر است؟</p>',

                    'options' => ['۵', '۷', '۱۷', '۶۰'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                    'explanation' => '<p>$x = 12 - 5 = 7$</p>',

                ],

                [

                    'body' => '<p>مساحت مربعی به ضلع ۵ سانتی‌متر چند سانتی‌متر مربع است؟</p>',

                    'options' => ['۱۰', '۲۰', '۲۵', '۳۰'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                    'explanation' => '<p>مساحت مربع = ضلع × ضلع = $5 × 5 = 25$</p>',

                ],

                [

                    'body' => '<p>کدام عدد اول است؟</p>',

                    'options' => ['۹', '۱۵', '۱۷', '۲۱'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>حاصل $\frac{1}{2} + \frac{1}{4}$ کدام است؟</p>',

                    'options' => ['$\frac{1}{6}$', '$\frac{2}{6}$', '$\frac{3}{4}$', '$\frac{1}{8}$'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>اگر محیط مستطیلی ۲۴ سانتی‌متر و طول آن ۸ سانتی‌متر باشد، عرض آن چقدر است؟</p>',

                    'options' => ['۴ سانتی‌متر', '۶ سانتی‌متر', '۸ سانتی‌متر', '۱۶ سانتی‌متر'],

                    'correct' => 1,

                    'difficulty' => 'medium',

                    'explanation' => '<p>محیط = $2(طول + عرض)$، پس $24 = 2(8 + عرض)$، $12 = 8 + عرض$، عرض = $4$</p>',

                ],

                [

                    'body' => '<p>حاصل $(x + 2)(x - 2)$ کدام است؟</p>',

                    'options' => ['$x^2 - 4$', '$x^2 + 4$', '$x^2 - 2x$', '$2x - 4$'],

                    'correct' => 1,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>معادله $2x - 6 = 0$ چه جوابی دارد؟</p>',

                    'options' => ['$x = 2$', '$x = 3$', '$x = -3$', '$x = 6$'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>اگر $a = 3$ و $b = 4$ باشد، حاصل $a^2 + b^2$ چیست؟</p>',

                    'options' => ['۷', '۱۲', '۲۵', '۴۹'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام گزینه نشان‌دهنده یک تابع خطی است؟</p>',

                    'options' => ['$y = x^2$', '$y = 2x + 1$', '$y = \frac{1}{x}$', '$y = \sqrt{x}$'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>مجموع زوایای داخلی یک مثلث چند درجه است؟</p>',

                    'options' => ['۹۰ درجه', '۱۸۰ درجه', '۲۷۰ درجه', '۳۶۰ درجه'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>حاصل $\sqrt{144}$ کدام است؟</p>',

                    'options' => ['۱۰', '۱۱', '۱۲', '۱۴'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>اگر $\log_{10} x = 2$ باشد، $x$ چقدر است؟</p>',

                    'options' => ['۲۰', '۱۰۰', '۱۰۰۰', '۲'],

                    'correct' => 2,

                    'difficulty' => 'hard',

                ],

                [

                    'body' => '<p>مشتق تابع $f(x) = x^3$ کدام است؟</p>',

                    'options' => ['$x^2$', '$3x^2$', '$3x$', '$x^4$'],

                    'correct' => 2,

                    'difficulty' => 'hard',

                ],

                [

                    'body' => '<p>انتگرال $\int 2x \, dx$ کدام است؟</p>',

                    'options' => ['$x^2 + C$', '$2x^2 + C$', '$x + C$', '$\frac{x^2}{2} + C$'],

                    'correct' => 1,

                    'difficulty' => 'hard',

                ],

                [

                    'body' => '<p>در یک دنباله حسابی، جمله اول ۳ و قدر نسبت ۲ است. جمله پنجم چیست؟</p>',

                    'options' => ['۹', '۱۱', '۱۳', '۱۵'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                    'explanation' => '<p>$a_n = a_1 + (n-1)d = 3 + (5-1)×2 = 3 + 8 = 11$</p>',

                ],

                [

                    'body' => '<p>حد $\lim_{x \to 0} \frac{\sin x}{x}$ برابر است با:</p>',

                    'options' => ['۰', '۱', '∞', 'تعریف نشده'],

                    'correct' => 2,

                    'difficulty' => 'special',

                ],

                [

                    'body' => '<p>ماتریس $A = \begin{pmatrix} 1 & 2 \\ 3 & 4 \end{pmatrix}$ دترمینان چندی دارد؟</p>',

                    'options' => ['۱۰', '-۲', '۲', '-۱۰'],

                    'correct' => 2,

                    'difficulty' => 'hard',

                    'explanation' => '<p>$det(A) = 1×4 - 2×3 = 4 - 6 = -2$</p>',

                ],

                [

                    'body' => '<p>در مختصات دکارتی، فاصله دو نقطه $(0,0)$ و $(3,4)$ چقدر است؟</p>',

                    'options' => ['۵', '۷', '۱۲', '۲۵'],

                    'correct' => 1,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>معادله $x^2 - 5x + 6 = 0$ چه ریشه‌هایی دارد؟</p>',

                    'options' => ['۱ و ۶', '۲ و ۳', '-۲ و -۳', '۱ و ۵'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>سینوس زاویه ۳۰ درجه چقدر است؟</p>',

                    'options' => ['$\frac{1}{2}$', '$\frac{\sqrt{2}}{2}$', '$\frac{\sqrt{3}}{2}$', '۱'],

                    'correct' => 1,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>حاصل $5!$ (فاکتوریل ۵) کدام است؟</p>',

                    'options' => ['۲۵', '۶۰', '۱۲۰', '۷۲۰'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام عدد گویا نیست؟</p>',

                    'options' => ['$\frac{22}{7}$', '$0.333...$', '$\sqrt{2}$', '$-5$'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>اگر $f(x) = 2x + 1$ باشد، $f(f(2))$ چقدر است؟</p>',

                    'options' => ['۵', '۹', '۱۱', '۱۳'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                    'explanation' => '<p>$f(2) = 2(2) + 1 = 5$، $f(5) = 2(5) + 1 = 11$</p>',

                ],

                [

                    'body' => '<p>مجموع اعداد صحیح از ۱ تا ۱۰ چقدر است؟</p>',

                    'options' => ['۴۵', '۵۰', '۵۵', '۱۰۰'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

            ],


            'physics' => [

                [

                    'body' => '<p>واحد نیرو در سیستم SI کدام است؟</p>',

                    'options' => ['ژول', 'نیوتن', 'وات', 'پاسکال'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>شتاب گرانش زمین تقریباً چند متر بر مجذور ثانیه است؟</p>',

                    'options' => ['۵', '۱۰', '۱۵', '۲۰'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>قانون دوم نیوتن کدام رابطه را بیان می‌کند؟</p>',

                    'options' => ['$F = ma$', '$F = mv$', '$E = mc^2$', '$P = IV$'],

                    'correct' => 1,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>انرژی جنبشی جسمی به جرم ۲ کیلوگرم با سرعت ۳ متر بر ثانیه چقدر است؟</p>',

                    'options' => ['۶ ژول', '۹ ژول', '۱۲ ژول', '۱۸ ژول'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                    'explanation' => '<p>$E_k = \frac{1}{2}mv^2 = \frac{1}{2}×2×9 = 9$ ژول</p>',

                ],

                [

                    'body' => '<p>سرعت نور در خلأ تقریباً چند متر بر ثانیه است؟</p>',

                    'options' => ['$3×10^6$', '$3×10^8$', '$3×10^{10}$', '$3×10^{12}$'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام کمیت برداری است؟</p>',

                    'options' => ['جرم', 'دما', 'سرعت', 'انرژی'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>فرمول کار فیزیکی کدام است؟</p>',

                    'options' => ['$W = Fd$', '$W = Pt$', '$W = mgh$', 'همه موارد'],

                    'correct' => 1,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>دوره تناوب آونگ ساده به کدام کمیت بستگی ندارد؟</p>',

                    'options' => ['طول آونگ', 'شتاب گرانش', 'جرم وزنه', 'هیچکدام'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>واحد فشار در SI کدام است؟</p>',

                    'options' => ['نیوتن', 'پاسکال', 'ژول', 'وات'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>در مدار الکتریکی، قانون اهم کدام رابطه را نشان می‌دهد؟</p>',

                    'options' => ['$V = IR$', '$P = IV$', '$Q = CV$', '$F = qE$'],

                    'correct' => 1,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>طول موج نور قرمز تقریباً چند نانومتر است؟</p>',

                    'options' => ['۴۰۰', '۵۵۰', '۷۰۰', '۹۰۰'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>جسمی از ارتفاع ۲۰ متری رها می‌شود. سرعت آن هنگام برخورد با زمین چقدر است؟ (g=10)</p>',

                    'options' => ['۱۰ m/s', '۲۰ m/s', '۳۰ m/s', '۴۰ m/s'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                    'explanation' => '<p>$v = \sqrt{2gh} = \sqrt{2×10×20} = \sqrt{400} = 20$ m/s</p>',

                ],

                [

                    'body' => '<p>قانون سوم نیوتن بیان‌کننده چیست؟</p>',

                    'options' => ['قانون اینرسی', 'عمل و عکس‌العمل', 'بقای انرژی', 'بقای تکانه'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>فرکانس موجی با دوره تناوب ۰.۱ ثانیه چقدر است؟</p>',

                    'options' => ['۰.۱ Hz', '۱ Hz', '۱۰ Hz', '۱۰۰ Hz'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام ذره بار مثبت دارد؟</p>',

                    'options' => ['الکترون', 'پروتون', 'نوترون', 'فوتون'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>انرژی پتانسیل گرانشی جسم ۵ کیلوگرمی در ارتفاع ۱۰ متری چقدر است؟ (g=10)</p>',

                    'options' => ['۵۰ J', '۱۰۰ J', '۵۰۰ J', '۲۵۰ J'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>در کدام فرآیند آنتروپی کاهش می‌یابد؟</p>',

                    'options' => ['ذوب یخ', 'انجماد آب', 'تبخیر آب', 'انبساط گاز'],

                    'correct' => 2,

                    'difficulty' => 'hard',

                ],

                [

                    'body' => '<p>رابطه انیشتین برای انرژی و جرم کدام است؟</p>',

                    'options' => ['$E = mc$', '$E = mc^2$', '$E = m^2c$', '$E = 2mc$'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>توان مصرفی یک لامپ ۶۰ واتی در ۱۰ ساعت چند کیلووات‌ساعت است؟</p>',

                    'options' => ['۰.۶', '۶', '۶۰', '۶۰۰'],

                    'correct' => 1,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>در پدیده فتوالکتریک، انرژی فوتون صرف چه می‌شود؟</p>',

                    'options' => ['فقط تابع کار', 'فقط انرژی جنبشی', 'تابع کار و انرژی جنبشی', 'هیچکدام'],

                    'correct' => 3,

                    'difficulty' => 'hard',

                ],

                [

                    'body' => '<p>مقاومت معادل دو مقاومت ۶ اهمی موازی چقدر است؟</p>',

                    'options' => ['۳ Ω', '۶ Ω', '۱۲ Ω', '۳۶ Ω'],

                    'correct' => 1,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>کدام پدیده نور را موجی نشان می‌دهد؟</p>',

                    'options' => ['فتوالکتریک', 'پراش', 'اثر کامپتون', 'تابش جسم سیاه'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>شدت میدان الکتریکی در فاصله r از بار نقطه‌ای با کدام رابطه متناسب است؟</p>',

                    'options' => ['$\frac{1}{r}$', '$\frac{1}{r^2}$', '$r$', '$r^2$'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>واحد ظرفیت خازن کدام است؟</p>',

                    'options' => ['فاراد', 'هانری', 'اهم', 'ولت'],

                    'correct' => 1,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>در حرکت پرتابی، شتاب افقی چقدر است؟</p>',

                    'options' => ['g', '۲g', 'صفر', 'متغیر'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

            ],


            'chemistry' => [

                [

                    'body' => '<p>عدد اتمی هیدروژن چند است؟</p>',

                    'options' => ['۰', '۱', '۲', '۳'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>فرمول شیمیایی آب کدام است؟</p>',

                    'options' => ['H₂O', 'HO₂', 'H₂O₂', 'OH'],

                    'correct' => 1,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام عنصر گاز نجیب است؟</p>',

                    'options' => ['اکسیژن', 'نیتروژن', 'هلیوم', 'هیدروژن'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>pH آب خالص چقدر است؟</p>',

                    'options' => ['۰', '۷', '۱۴', '۱'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام پیوند قوی‌تر است؟</p>',

                    'options' => ['پیوند هیدروژنی', 'پیوند یونی', 'نیروی واندروالس', 'پیوند دوقطبی-دوقطبی'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>عدد جرمی برابر است با:</p>',

                    'options' => ['تعداد پروتون‌ها', 'تعداد نوترون‌ها', 'تعداد پروتون‌ها + نوترون‌ها', 'تعداد الکترون‌ها'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام ترکیب کووالانسی است؟</p>',

                    'options' => ['NaCl', 'CO₂', 'MgO', 'KBr'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>واحد غلظت مولار کدام است؟</p>',

                    'options' => ['g/L', 'mol/L', 'g/mol', 'L/mol'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>عدد آووگادرو تقریباً چقدر است؟</p>',

                    'options' => ['$6×10^{20}$', '$6×10^{23}$', '$6×10^{26}$', '$6×10^{10}$'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>در واکنش احتراق کامل، محصولات کدامند؟</p>',

                    'options' => ['CO و H₂O', 'CO₂ و H₂O', 'CO₂ و H₂', 'C و H₂O'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام اسید قوی است؟</p>',

                    'options' => ['CH₃COOH', 'H₂CO₃', 'HCl', 'H₂S'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>در لایه والانس اتم کربن چند الکترون وجود دارد؟</p>',

                    'options' => ['۲', '۴', '۶', '۸'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>ایزوتوپ‌ها در کدام ویژگی متفاوتند؟</p>',

                    'options' => ['تعداد پروتون', 'تعداد نوترون', 'تعداد الکترون', 'عدد اتمی'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>کدام فلز در آب شناور می‌ماند؟</p>',

                    'options' => ['آهن', 'مس', 'سدیم', 'جیوه'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>نام IUPAC ترکیب CH₃-CH₂-CH₃ کدام است؟</p>',

                    'options' => ['متان', 'اتان', 'پروپان', 'بوتان'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام واکنش گرماده است؟</p>',

                    'options' => ['تبخیر آب', 'ذوب یخ', 'احتراق متان', 'فتوسنتز'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>الکترونگاتیوی کدام عنصر بیشتر است؟</p>',

                    'options' => ['سدیم', 'کربن', 'اکسیژن', 'فلوئور'],

                    'correct' => 4,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>محلول با pH=3 چند بار اسیدی‌تر از محلول pH=5 است؟</p>',

                    'options' => ['۲ برابر', '۱۰ برابر', '۱۰۰ برابر', '۱۰۰۰ برابر'],

                    'correct' => 3,

                    'difficulty' => 'hard',

                ],

                [

                    'body' => '<p>کدام ترکیب آلی است؟</p>',

                    'options' => ['NaCl', 'H₂O', 'C₆H₁₂O₆', 'CO₂'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>در واکنش اکسایش-کاهش، ماده کاهنده:</p>',

                    'options' => ['الکترون می‌گیرد', 'الکترون از دست می‌دهد', 'پروتون می‌گیرد', 'پروتون از دست می‌دهد'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>جرم مولی گلوکز (C₆H₁₂O₆) چند گرم بر مول است؟</p>',

                    'options' => ['۱۲۰', '۱۵۰', '۱۸۰', '۲۰۰'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>کدام گاز در لایه اوزون وجود دارد؟</p>',

                    'options' => ['O₂', 'O₃', 'N₂', 'CO₂'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>آرایش الکترونی اتم نئون (Z=10) کدام است؟</p>',

                    'options' => ['1s²2s²2p⁶', '1s²2s²2p⁴', '1s²2s²2p⁵', '1s²2s²2p³'],

                    'correct' => 1,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>در شرایط استاندارد (STP) حجم یک مول گاز ایده‌آل چقدر است؟</p>',

                    'options' => ['۱۱.۲ لیتر', '۲۲.۴ لیتر', '۴۴.۸ لیتر', '۶.۰۲ لیتر'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>کدام عامل سرعت واکنش را افزایش نمی‌دهد؟</p>',

                    'options' => ['افزایش دما', 'افزایش غلظت', 'افزایش کاتالیزور', 'کاهش سطح تماس'],

                    'correct' => 4,

                    'difficulty' => 'medium',

                ],

            ],


            'biology' => [

                [

                    'body' => '<p>واحد ساختاری و عملی موجودات زنده چیست؟</p>',

                    'options' => ['بافت', 'سلول', 'اندام', 'دستگاه'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام اندامک مسئول تنفس سلولی است؟</p>',

                    'options' => ['ریبوزوم', 'میتوکندری', 'کلروپلاست', 'هسته'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>DNA مخفف چیست؟</p>',

                    'options' => ['دئوکسی‌ریبونوکلئیک اسید', 'ریبونوکلئیک اسید', 'آدنوزین تری‌فسفات', 'هیچکدام'],

                    'correct' => 1,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>فتوسنتز در کدام اندامک انجام می‌شود؟</p>',

                    'options' => ['میتوکندری', 'کلروپلاست', 'واکوئل', 'ریبوزوم'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام گروه خونی گیرنده عمومی است؟</p>',

                    'options' => ['A', 'B', 'AB', 'O'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام ویتامین محلول در چربی است؟</p>',

                    'options' => ['ویتامین C', 'ویتامین B12', 'ویتامین D', 'ویتامین B1'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>غشای سلولی از چه ترکیباتی ساخته شده است؟</p>',

                    'options' => ['پروتئین و کربوهیدرات', 'لیپید و پروتئین', 'فقط لیپید', 'فقط پروتئین'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>تعداد کروموزوم‌های انسان چند جفت است؟</p>',

                    'options' => ['۲۲ جفت', '۲۳ جفت', '۲۴ جفت', '۴۶ جفت'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام هورمون قند خون را کاهش می‌دهد؟</p>',

                    'options' => ['گلوکاگون', 'انسولین', 'آدرنالین', 'تیروکسین'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام سلول خونی فاقد هسته است؟</p>',

                    'options' => ['گلبول سفید', 'گلبول قرمز', 'پلاکت', 'هر سه'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>آنزیم‌ها از چه موادی ساخته شده‌اند؟</p>',

                    'options' => ['کربوهیدرات', 'لیپید', 'پروتئین', 'اسید نوکلئیک'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام اندام غده درون‌ریز و برون‌ریز است؟</p>',

                    'options' => ['کبد', 'کلیه', 'لوزالمعده', 'تیروئید'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>ATP مخفف چیست؟</p>',

                    'options' => ['آدنوزین تری‌فسفات', 'آدنین تری‌فسفات', 'آدنوزین دی‌فسفات', 'آدنین دی‌فسفات'],

                    'correct' => 1,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام بخش مغز مسئول تعادل است؟</p>',

                    'options' => ['مخ', 'مخچه', 'بصل‌النخاع', 'تالاموس'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>میوز چند سلول تولید می‌کند؟</p>',

                    'options' => ['۲ سلول', '۴ سلول', '۸ سلول', '۱۶ سلول'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام ماده ژنتیکی تک‌رشته‌ای است؟</p>',

                    'options' => ['DNA', 'RNA', 'هر دو', 'هیچکدام'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>فرآیند تبدیل گلوکز به پیروات چه نام دارد؟</p>',

                    'options' => ['گلیکولیز', 'چرخه کربس', 'زنجیره انتقال الکترون', 'تخمیر'],

                    'correct' => 1,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>کدام نوکلئوتید در RNA به جای تیمین وجود دارد؟</p>',

                    'options' => ['آدنین', 'گوانین', 'سیتوزین', 'یوراسیل'],

                    'correct' => 4,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>کدام بیماری ژنتیکی مغلوب است؟</p>',

                    'options' => ['هانتینگتون', 'فیبروز کیستیک', 'آکندروپلازی', 'نوروفیبروماتوز'],

                    'correct' => 2,

                    'difficulty' => 'hard',

                ],

                [

                    'body' => '<p>در کدام مرحله تقسیم میتوز کروموزوم‌ها به دو قطب می‌روند؟</p>',

                    'options' => ['پروفاز', 'متافاز', 'آنافاز', 'تلوفاز'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

            ],


            'persian-literature' => [

                [

                    'body' => '<p>در بیت «سعدی اگر چه سخن گوی نکته‌دان است / با سخنان تو کجا ماند دوران است» کدام آرایه ادبی دیده می‌شود؟</p>',

                    'options' => ['تشبیه', 'استعاره', 'تلمیح', 'مراعات نظیر'],

                    'correct' => 4,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>«آفتاب» در جمله «آفتاب آمد دلیل آفتاب» چه نقشی دارد؟</p>',

                    'options' => ['نهاد', 'مفعول', 'مسند', 'متمم'],

                    'correct' => 1,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام گزینه جمله پرسشی است؟</p>',

                    'options' => ['کتاب را بخوان', 'چه کتابی خواندی', 'کتاب را خواندم', 'کتاب خوبی بود'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>در جمله «هوا بسیار سرد بود» کدام کلمه قید است؟</p>',

                    'options' => ['هوا', 'بسیار', 'سرد', 'بود'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>معنی کلمه «مستغنی» کدام است؟</p>',

                    'options' => ['نیازمند', 'بی‌نیاز', 'محتاج', 'فقیر'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>«گلستان» اثر کیست؟</p>',

                    'options' => ['حافظ', 'فردوسی', 'سعدی', 'مولوی'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام گزینه غلط املایی دارد؟</p>',

                    'options' => ['ظلمت', 'سحر', 'قدرت', 'صحرا'],

                    'correct' => 1,

                    'difficulty' => 'medium',

                    'explanation' => '<p>ظلمت صحیح است، ولی اگر منظور تاریکی باشد باید با «ظ» نوشته شود.</p>',

                ],

                [

                    'body' => '<p>در بیت «دل بی‌تو به جان آمد، جان هم به لب آمد» کدام آرایه دیده می‌شود؟</p>',

                    'options' => ['تشبیه', 'کنایه', 'مجاز', 'تضاد'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>شاهنامه چند بیت دارد؟</p>',

                    'options' => ['حدود ۳۰ هزار', 'حدود ۵۰ هزار', 'حدود ۶۰ هزار', 'حدود ۱۰۰ هزار'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>کدام کلمه هم‌خانواده «علم» نیست؟</p>',

                    'options' => ['عالم', 'معلوم', 'عمل', 'علوم'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>در جمله «دانش‌آموز باهوش درس خواند» صفت کدام است؟</p>',

                    'options' => ['دانش‌آموز', 'باهوش', 'درس', 'خواند'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>«مثنوی معنوی» اثر کیست؟</p>',

                    'options' => ['حافظ', 'سعدی', 'مولوی', 'عطار'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام جمله مجهول است؟</p>',

                    'options' => ['علی کتاب را خواند', 'کتاب خوانده شد', 'کتاب روی میز است', 'علی کتاب دارد'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>در «چشم امید» کدام آرایه به کار رفته است؟</p>',

                    'options' => ['تشخیص', 'اضافه تشبیهی', 'کنایه', 'استعاره'],

                    'correct' => 2,

                    'difficulty' => 'hard',

                ],

                [

                    'body' => '<p>جمع مکسر «کتاب» کدام است؟</p>',

                    'options' => ['کتب', 'کتاب‌ها', 'کتابان', 'کتابات'],

                    'correct' => 1,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>در جمله «او به مدرسه رفت» حرف اضافه کدام است؟</p>',

                    'options' => ['او', 'به', 'مدرسه', 'رفت'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام شاعر لقب «خداوندگار غزل» دارد؟</p>',

                    'options' => ['سعدی', 'حافظ', 'مولوی', 'خیام'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>در بیت «ز دریا می‌رود لؤلؤ به گوهر» کدام آرایه دیده می‌شود؟</p>',

                    'options' => ['تشبیه', 'واج‌آرایی', 'مراعات نظیر', 'تضاد'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>«بوستان» در کدام قالب سروده شده است؟</p>',

                    'options' => ['غزل', 'قصیده', 'مثنوی', 'رباعی'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>معنی «سَحَر» کدام است؟</p>',

                    'options' => ['شب', 'صبح زود', 'غروب', 'نیمه‌شب'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

            ],


            'english' => [

                [

                    'body' => '<p>Choose the correct answer: She ___ to school every day.</p>',

                    'options' => ['go', 'goes', 'going', 'gone'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>What is the past tense of "eat"?</p>',

                    'options' => ['eated', 'eaten', 'ate', 'eating'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>Choose the correct sentence:</p>',

                    'options' => ['He don\'t like coffee', 'He doesn\'t like coffee', 'He not like coffee', 'He isn\'t like coffee'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>The word "beautiful" is a/an ___.</p>',

                    'options' => ['noun', 'verb', 'adjective', 'adverb'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>If I ___ rich, I would travel the world.</p>',

                    'options' => ['am', 'was', 'were', 'be'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>The synonym of "happy" is:</p>',

                    'options' => ['sad', 'joyful', 'angry', 'tired'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>Choose the correct preposition: I\'m interested ___ music.</p>',

                    'options' => ['on', 'at', 'in', 'to'],

                    'correct' => 3,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>The antonym of "ancient" is:</p>',

                    'options' => ['old', 'modern', 'historic', 'classic'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>She has been working here ___ 2019.</p>',

                    'options' => ['for', 'since', 'from', 'at'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>Which sentence is in passive voice?</p>',

                    'options' => ['John wrote the letter', 'The letter was written by John', 'John is writing the letter', 'John will write the letter'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>I ___ my homework when you called.</p>',

                    'options' => ['did', 'was doing', 'have done', 'do'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>The plural of "child" is:</p>',

                    'options' => ['childs', 'childes', 'children', 'childrens'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>Which word is spelled correctly?</p>',

                    'options' => ['recieve', 'receive', 'receve', 'receeve'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>They ___ finished the project by tomorrow.</p>',

                    'options' => ['will have', 'will', 'have', 'had'],

                    'correct' => 1,

                    'difficulty' => 'hard',

                ],

                [

                    'body' => '<p>The comparative form of "good" is:</p>',

                    'options' => ['gooder', 'more good', 'better', 'best'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>"Although" is a ___.</p>',

                    'options' => ['preposition', 'conjunction', 'adverb', 'interjection'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>He asked me ___ I was from.</p>',

                    'options' => ['what', 'where', 'which', 'who'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>___ you mind closing the window?</p>',

                    'options' => ['Do', 'Would', 'Are', 'Have'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>The meaning of "abandon" is:</p>',

                    'options' => ['keep', 'leave', 'find', 'return'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>This is the book ___ I told you about.</p>',

                    'options' => ['who', 'which', 'what', 'whose'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

            ],


            'religion' => [

                [

                    'body' => '<p>اولین سوره قرآن کریم کدام است؟</p>',

                    'options' => ['البقره', 'الفاتحه', 'الناس', 'الاخلاص'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>تعداد سوره‌های قرآن کریم چند است؟</p>',

                    'options' => ['۱۱۰', '۱۱۴', '۱۲۰', '۱۲۴'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام نماز واجب یومیه نیست؟</p>',

                    'options' => ['صبح', 'شب', 'مغرب', 'عشا'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>پیامبر اسلام (ص) در کدام شهر متولد شدند؟</p>',

                    'options' => ['مدینه', 'مکه', 'طائف', 'یثرب'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام ماه، ماه مبارک رمضان است؟</p>',

                    'options' => ['هفتم', 'هشتم', 'نهم', 'دهم'],

                    'correct' => 3,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>اولین امام شیعیان کیست؟</p>',

                    'options' => ['امام حسین (ع)', 'امام علی (ع)', 'امام حسن (ع)', 'امام سجاد (ع)'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>نماز جمعه چند رکعت است؟</p>',

                    'options' => ['۲ رکعت', '۳ رکعت', '۴ رکعت', '۵ رکعت'],

                    'correct' => 1,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>کدام سوره «قلب قرآن» نامیده می‌شود؟</p>',

                    'options' => ['البقره', 'یاسین', 'الرحمن', 'الاخلاص'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>واقعه عاشورا در چه سالی رخ داد؟</p>',

                    'options' => ['۶۰ هجری', '۶۱ هجری', '۶۲ هجری', '۶۳ هجری'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>خمس چند درصد است؟</p>',

                    'options' => ['۱۰ درصد', '۲۰ درصد', '۲.۵ درصد', '۵ درصد'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

            ],


            'history' => [

                [

                    'body' => '<p>انقلاب اسلامی ایران در چه سالی پیروز شد؟</p>',

                    'options' => ['۱۳۵۶', '۱۳۵۷', '۱۳۵۸', '۱۳۵۹'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>اولین پادشاه سلسله صفویه که بود؟</p>',

                    'options' => ['شاه عباس', 'شاه اسماعیل', 'شاه طهماسب', 'شاه سلطان حسین'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>جنگ جهانی اول در چه سالی آغاز شد؟</p>',

                    'options' => ['۱۹۱۲', '۱۹۱۴', '۱۹۱۸', '۱۹۳۹'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>کدام پادشاه هخامنشی امپراتوری را بنیان نهاد؟</p>',

                    'options' => ['داریوش', 'کوروش', 'خشایارشا', 'اردشیر'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>پایتخت ایران در دوره صفویه کجا بود؟</p>',

                    'options' => ['تبریز', 'اصفهان', 'تهران', 'شیراز'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>انقلاب مشروطه ایران در چه سالی رخ داد؟</p>',

                    'options' => ['۱۲۸۰', '۱۲۸۵', '۱۲۹۰', '۱۲۹۵'],

                    'correct' => 2,

                    'difficulty' => 'medium',

                ],

                [

                    'body' => '<p>کدام جنگ بین ایران و روسیه رخ داد؟</p>',

                    'options' => ['جنگ‌های صلیبی', 'جنگ‌های ایران و روس', 'جنگ جهانی اول', 'جنگ چالدران'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>جنگ تحمیلی چند سال طول کشید؟</p>',

                    'options' => ['۶ سال', '۸ سال', '۱۰ سال', '۱۲ سال'],

                    'correct' => 2,

                    'difficulty' => 'easy',

                ],

                [

                    'body' => '<p>فتح مکه توسط پیامبر (ص) در چه سالی رخ داد؟</p>',

                    'options' => ['۶ هجری', '۸ هجری', '۱۰ هجری', '۱۲ هجری'],

                    'correct' => 2,

                    'difficulty' => 'hard',

                ],

                [

                    'body' => '<p>کدام سلسله پس از ساسانیان در ایران حکومت کرد؟</p>',

                    'options' => ['صفاریان', 'طاهریان', 'خلافت اموی', 'سامانیان'],

                    'correct' => 3,

                    'difficulty' => 'hard',

                ],

            ],

        ];

    }

}
