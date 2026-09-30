<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        Partner::query()->delete();

        $partners = [
            ['top-design.jpeg', 'Top Design Solutions'],
            ['mobco.jpeg', 'MOBCO Construction'],
            ['nesma-man.jpeg', 'Nesma & Partners / MAN Enterprise'],
            ['bright-future.jpeg', 'المستقبل المشرق'],
            ['al-rashed.jpeg', 'Saleh Abdulaziz Al Rashed & Sons'],
            ['tafasil.jpeg', 'TAFASIL'],
            ['tasco.jpeg', 'TASCO'],
        ];

        foreach ($partners as $i => [$logo, $name]) {
            Partner::create(['name' => $name, 'logo' => "seed/partners/{$logo}", 'is_active' => true, 'sort_order' => $i + 1]);
        }
    }
}
