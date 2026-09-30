<?php

namespace Database\Seeders;

use App\Models\CareerJob;
use Illuminate\Database\Seeder;

class CareerJobSeeder extends Seeder
{
    public function run(): void
    {
        CareerJob::query()->delete();
        $type = ['ar' => 'دوام كامل', 'en' => 'Full time'];
        $location = ['ar' => 'الرياض', 'en' => 'Riyadh'];

        $jobs = [
            [['مهندس موقع', 'Site engineer'], ['إدارة أعمال الموقع ومتابعة التنفيذ والتنسيق اليومي', 'Manage site execution and daily coordination']],
            [['مهندس MEP', 'MEP engineer'], ['متابعة الأعمال الكهربائية والميكانيكية والسباكة', 'Coordinate electrical, mechanical and plumbing works']],
            [['مسؤول مشتريات', 'Procurement officer'], ['إدارة طلبات المواد والتوريد ومتابعة الموردين', 'Manage material requests, supply and vendor follow-up']],
        ];

        foreach ($jobs as $i => [$title, $description]) {
            CareerJob::create([
                'title' => ['ar' => $title[0], 'en' => $title[1]],
                'description' => ['ar' => $description[0], 'en' => $description[1]],
                'employment_type' => $type,
                'location' => $location,
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
