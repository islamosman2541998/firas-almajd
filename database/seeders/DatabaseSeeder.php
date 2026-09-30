<?php

namespace Database\Seeders;

use App\Support\SiteCache;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ServiceSeeder::class,
            SliderSeeder::class,
            ProjectSeeder::class,
            GallerySeeder::class,
            CertificateSeeder::class,
            PartnerSeeder::class,
            FaqSeeder::class,
            CareerJobSeeder::class,
            PageSeeder::class,
            MenuSeeder::class,
            UiTranslationSeeder::class,
        ]);

        SiteCache::flush();
    }
}
