<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminsTableSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            PaymentMethodsTableSeeder::class,
            RolePermissionSeeder::class,
            DepartmentsTableSeeder::class,
            SdfrSchoolsTableSeeder::class,
            SdfrStudentsTableSeeder::class,
            CcFieldSeeder::class,
            ExamPeriodSeeder::class,
            AcademicAdvisorPermissionSeeder::class,
            SiteAcquisitionRoleSeeder::class,
            PhoneAcquisitionRoleSeeder::class,
            GeneralSettingsTableSeeder::class,
            AvatarSeeder::class,
            EducationLevelsTableSeeder::class,
            CcFieldsTableSeeder::class,
            CcGradesTableSeeder::class,
            CcSubjectsTableSeeder::class,
            CcChaptersTableSeeder::class,
            CcTopicsTableSeeder::class,
            SubjectsTableSeeder::class,
            ExamCountdownSettingsTableSeeder::class,
            ExamCountdownEventsTableSeeder::class,
            TrialClassificationProjectSeeder::class,
            AssessmentSeeder::class,
            GradePriceSeeder::class,
            PhoneAcquisitionDemoSeeder::class
        ]);
    }
}
