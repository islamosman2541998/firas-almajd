<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::query()->where('slug', 'company-profile')->get()->each->delete();

        $page = Page::create([
            'slug' => 'company-profile',
            'title' => ['ar' => 'ملف الشركة', 'en' => 'Company profile'],
            'description' => [
                'ar' => "شركة فراس المجد العمرانية تجمع المقاولات والتشطيبات والبنية التحتية والنقل والتوريد وتنسيق المواقع والصيانة في نطاق عمل واحد.\nهذه صفحة تجريبية يمكن تعديلها أو حذفها من لوحة التحكم، وتوضح طريقة عرض الصور والفيديوهات وملفات PDF في الصفحات المخصصة.",
                'en' => "Firas Al Majd Urban Company brings contracting, fit-out, infrastructure, transport, supply, landscaping and maintenance together in one scope of work.\nThis is a sample page you can edit or delete from the dashboard. It shows how custom pages display images, videos and PDF files.",
            ],
            'image' => 'seed/courtyard.webp',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        foreach ([['seed/project-villa.png', 'wide'], ['seed/cafe.webp', 'tall'], ['seed/materials.webp', 'normal'], ['seed/logistics.webp', 'normal']] as $i => [$path, $layout]) {
            $page->media()->create(['collection' => 'gallery', 'type' => 'image', 'path' => $path, 'layout' => $layout, 'title' => ['ar' => '', 'en' => ''], 'sort_order' => $i + 1]);
        }
    }
}
