<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        GalleryItem::query()->delete();

        $items = [
            ['seed/project-villa.png', 'wide', 'فيلا سكنية معاصرة', 'Contemporary private villa'],
            ['seed/project-cafe.png', 'tall', 'تجهيز مساحة تجارية', 'Commercial fit-out'],
            ['seed/project-landscape.png', 'normal', 'تنسيق فناء سكني', 'Residential courtyard landscape'],
            ['seed/construction.webp', 'normal', 'أعمال إنشاء وتجهيز مواقع', 'Construction and site preparation'],
            ['seed/cafe.webp', 'wide', 'تفاصيل تشطيبات داخلية', 'Interior fit-out details'],
            ['seed/courtyard.webp', 'tall', 'Landscape ومساحات خارجية', 'Landscape and outdoor spaces'],
            ['seed/maintenance.webp', 'normal', 'أعمال صيانة وتجهيز مرافق', 'Maintenance and facility works'],
        ];

        foreach ($items as $i => [$image, $layout, $ar, $en]) {
            GalleryItem::create(['image' => $image, 'layout' => $layout, 'title' => ['ar' => $ar, 'en' => $en], 'is_active' => true, 'sort_order' => $i + 1]);
        }
    }
}
