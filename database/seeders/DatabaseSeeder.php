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
            UsersTableSeeder::class,
            AdminsTableSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            CategoriesTableSeeder::class,
            CategoryFeaturesTableSeeder::class,
            ProductsTableSeeder::class,
            ProductImagesTableSeeder::class,
            SeoItemsTableSeeder::class,
            ContactUsTableSeeder::class,
            PaymentMethodsTableSeeder::class,
            RolePermissionSeeder::class,
            DepartmentsTableSeeder::class,

            BlogsTableSeeder::class,
            BlogImagesTableSeeder::class,
            BlogSeoItemsTableSeeder::class,
            SdfrSchoolsTableSeeder::class,
            SdfrStudentsTableSeeder::class,
            StoriesTableSeeder::class,
            OrdersTableSeeder::class,
            OrderItemsTableSeeder::class,
            PersonalInformationTableSeeder::class,
            PaymentsTableSeeder::class,
            StudentsTableSeeder::class,
            ReportsTableSeeder::class,
            SubjectSeeder::class,
            TypedExamSeeder::class,


        ]);


    }
}
