<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        Slider::query()->delete();

        Slider::create([
            'media_type' => 'image',
            'image' => 'seed/hero.webp',
            'title' => ['ar' => 'شركة فراس المجد العمرانية', 'en' => 'Firas Al Majd Urban Company'],
            'description' => ['ar' => 'من الأساس إلى التسليم، ننفذ مشروعك بخبرة متكاملة', 'en' => 'From the first foundation to the finishing touch. Your partner in construction and development, with integrated solutions for your vision.'],
            'show_button' => true,
            'button_text' => ['ar' => 'اكتشف خدماتنا', 'en' => 'Explore our services'],
            'button_url' => 'route:services.index',
            'overlay_opacity' => 100,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Slider::create([
            'media_type' => 'image',
            'image' => 'seed/construction.webp',
            'title' => ['ar' => 'تخصصات عدة ورؤية واحدة', 'en' => 'Multiple disciplines. One vision.'],
            'description' => ['ar' => 'نجمع المقاولات والتشطيبات والبنية التحتية وخدمات الموقع في نطاق عمل واحد', 'en' => 'Contracting, fit-out, infrastructure and site services, coordinated in one scope of work.'],
            'show_button' => true,
            'button_text' => ['ar' => 'تواصل معنا', 'en' => 'Contact us'],
            'button_url' => 'route:contact',
            'overlay_opacity' => 100,
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}
