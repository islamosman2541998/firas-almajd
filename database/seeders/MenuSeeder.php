<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Service;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        MenuItem::query()->delete();

        $items = [
            ['home', 'الرئيسية', 'Home'],
            ['about', 'من نحن', 'About us'],
            ['services.index', 'الخدمات', 'Services'],
            ['projects', 'المشاريع', 'Projects'],
            ['gallery', 'معرض الصور', 'Gallery'],
            ['certificates', 'الشهادات المعتمدة', 'Certifications'],
            ['careers', 'الوظائف', 'Careers'],
        ];

        foreach ($items as $i => [$route, $ar, $en]) {
            $item = MenuItem::create([
                'location' => 'header',
                'title' => ['ar' => $ar, 'en' => $en],
                'type' => 'route',
                'route_name' => $route,
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);

            if ($route === 'services.index') {
                foreach (Service::query()->ordered()->get() as $j => $service) {
                    MenuItem::create([
                        'location' => 'header',
                        'parent_id' => $item->id,
                        'title' => $service->getTranslations('title'),
                        'type' => 'service',
                        'linkable_type' => 'service',
                        'linkable_id' => $service->id,
                        'is_active' => true,
                        'sort_order' => $j + 1,
                    ]);
                }
            }
        }
    }
}
