<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::query()->delete();
        $services = Service::query()->pluck('id', 'slug');
        $riyadh = ['ar' => 'الرياض', 'en' => 'Riyadh'];

        $projects = [
            ['seed/project-villa.png', 'construction', ['ar' => 'فيلا سكنية معاصرة', 'en' => 'Contemporary private villa'], ['ar' => 'مقاولات وتشطيبات خارجية', 'en' => 'Construction and exterior finishes']],
            ['seed/project-cafe.png', 'fitout', ['ar' => 'تجهيز مساحة تجارية', 'en' => 'Commercial space fit-out'], ['ar' => 'تشطيبات وJoinery وأعمال MEP', 'en' => 'Fit-out, joinery and MEP works']],
            ['seed/project-landscape.png', 'construction', ['ar' => 'تنسيق فناء سكني', 'en' => 'Residential courtyard landscape'], ['ar' => 'Landscape وشبكات ري', 'en' => 'Landscape and irrigation']],
        ];

        foreach ($projects as $i => [$image, $service, $title, $category]) {
            Project::create([
                'title' => $title,
                'location' => $riyadh,
                'category' => $category,
                'service_id' => $services[$service] ?? null,
                'image' => $image,
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
