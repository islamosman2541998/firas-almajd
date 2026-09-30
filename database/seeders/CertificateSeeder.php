<?php

namespace Database\Seeders;

use App\Models\Certificate;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        Certificate::query()->delete();

        foreach (range(1, 4) as $n) {
            Certificate::create([
                'image' => "seed/certificates/certificate-{$n}.svg",
                'title' => ['ar' => '', 'en' => ''],
                'is_active' => true,
                'sort_order' => $n,
            ]);
        }
    }
}
