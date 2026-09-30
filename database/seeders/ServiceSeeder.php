<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $t = fn ($ar, $en) => ['ar' => $ar, 'en' => $en];

        $services = [
            'construction' => [
                'image' => 'seed/construction.webp',
                'title' => $t('المقاولات والبنية التحتية', 'Construction & infrastructure'),
                'short_description' => $t('تنفيذ الإنشاءات وتجهيز المواقع وفق نطاق المشروع', 'Construction and site preparation tailored to the project scope'),
                'scope' => [$t('الإنشاءات والأعمال الخرسانية', 'Structural and concrete works'), $t('أسقف Post-Tension', 'Post-tensioned slabs'), $t('الحفر والردم والهدم والترحيل', 'Excavation, backfilling & demolition'), $t('الأسفلت وأعمال البنية التحتية', 'Asphalt & infrastructure')],
                'prep' => [$t('نوع المنشأة وموقعها', 'Building type & location'), $t('المخططات الإنشائية والكميات إن توفرت', 'Structural drawings & quantities, if available'), $t('حالة الموقع والأعمال المطلوبة', 'Site condition & required works')],
                'related' => ['supply', 'logistics', 'maintenance'],
            ],
            'fitout' => [
                'image' => 'seed/cafe.webp',
                'title' => $t('التشطيبات والتجهيز الداخلي', 'Fit-out & interior works'),
                'short_description' => $t('تشطيبات وتجهيز متكامل لمساحات جاهزة للاستلام', 'Complete fit-out for spaces ready for handover'),
                'scope' => [$t('تجهيز المقاهي وتسليم مفتاح', 'Turnkey café fit-outs'), $t('أعمال Joinery والأثاث والديكور', 'Joinery, furniture & interiors'), $t('تركيب الأرضيات والمطابخ', 'Flooring & kitchen installation'), $t('الدهانات وتشطيب الواجهات', 'Painting & façade finishes')],
                'prep' => [$t('مساحة المكان والاستخدام المطلوب', 'Space area & intended use'), $t('المخططات والتصور التصميمي إن توفرا', 'Drawings & design concept, if available'), $t('الخامات والتشطيبات المطلوبة', 'Required materials & finishes')],
                'related' => ['supply', 'maintenance', 'landscape'],
            ],
            'logistics' => [
                'image' => 'seed/logistics.webp',
                'title' => $t('النقل والخدمات اللوجستية', 'Transport & logistics'),
                'short_description' => $t('نقل المياه والمواد بما يناسب احتياجات الموقع', 'Water and material transport suited to site needs'),
                'scope' => [$t('نقل المياه للمشروعات', 'Project water transport'), $t('Flatbed Trailers والقلابات', 'Flatbed trailers & tipper trucks'), $t('تناكر Fire Truck', 'Fire truck water tankers'), $t('نقل مواد البناء والركام', 'Building materials & aggregate transport')],
                'prep' => [$t('نوع الحمولة والكميات', 'Load type & quantities'), $t('موقع التحميل والتفريغ', 'Loading & unloading locations'), $t('مواعيد النقل وإمكانية دخول الموقع', 'Transport schedule & site access')],
                'related' => ['supply', 'construction', 'landscape'],
            ],
            'supply' => [
                'image' => 'seed/materials.webp',
                'title' => $t('توريد المواد وتأجير المعدات', 'Materials & equipment'),
                'short_description' => $t('توريد مواد وأدوات ومعدات المشروعات', 'Materials, tools and equipment for project sites'),
                'scope' => [$t('مواد البناء والحجر الديكوري', 'Building materials & decorative stone'), $t('Base Course وSubbase وCrushed Sand', 'Base course, sub-base & crushed sand'), $t('الأدوات الكهربائية والسباكة', 'Electrical & plumbing supplies'), $t('تأجير معدات المشروعات', 'Project equipment rental')],
                'prep' => [$t('أسماء المواد والمواصفات', 'Material names & specifications'), $t('الكميات أو نوع المعدات المطلوبة', 'Quantities or required equipment type'), $t('موقع التوريد والفترة المطلوبة', 'Delivery site & required period')],
                'related' => ['construction', 'fitout', 'logistics'],
            ],
            'landscape' => [
                'image' => 'seed/courtyard.webp',
                'title' => $t('تنسيق المواقع وشبكات الري', 'Landscape & irrigation'),
                'short_description' => $t('أعمال Landscape وشبكات ري تناسب الموقع والمناخ', 'Landscape and irrigation suited to the site and climate'),
                'scope' => [$t('توريد وزراعة النجيل والنباتات', 'Turf & plant supply and planting'), $t('الأشجار المعمرة وأنواع النخيل', 'Mature trees & palm varieties'), $t('شبكات الري والتحكم وTimers', 'Irrigation networks, controls & timers'), $t('معالجة الملوحة والصيانة الزراعية', 'Salinity treatment & landscape care')],
                'prep' => [$t('مساحة الموقع وحالة التربة', 'Site area & soil conditions'), $t('مصدر المياه والزراعة المطلوبة', 'Water source & planting needs'), $t('نطاق شبكة الري والصيانة', 'Irrigation & maintenance scope')],
                'related' => ['supply', 'logistics', 'maintenance'],
            ],
            'maintenance' => [
                'image' => 'seed/maintenance.webp',
                'title' => $t('الصيانة والأعمال الكهروميكانيكية', 'Maintenance & MEP'),
                'short_description' => $t('صيانة للمباني والمرافق حسب احتياج الموقع', 'Building and facility maintenance tailored to site needs'),
                'scope' => [$t('أعمال الكهرباء والميكانيكا والسباكة', 'Electrical, mechanical & plumbing works'), $t('كشف تسربات المياه', 'Water leak detection'), $t('معالجة التشققات والرطوبة', 'Crack & damp treatment'), $t('عزل الأسطح والخزانات', 'Roof & tank waterproofing')],
                'prep' => [$t('نوع المبنى والمشكلة القائمة', 'Building type & current issue'), $t('صور أو وصف تفصيلي للحالة', 'Photos or a detailed description'), $t('موقع الأعمال وإمكانية المعاينة', 'Work location & inspection access')],
                'related' => ['construction', 'fitout', 'landscape'],
            ],
        ];

        $order = 0;
        foreach ($services as $slug => $data) {
            $service = Service::query()->updateOrCreate(['slug' => $slug], [
                'title' => $data['title'],
                'short_description' => $data['short_description'],
                'image' => $data['image'],
                'scope_items' => $data['scope'],
                'prep_items' => $data['prep'],
                'show_on_home' => true,
                'is_active' => true,
                'sort_order' => ++$order,
            ]);

            $service->saveSeo([
                'meta_title' => ['ar' => 'فراس المجد | '.$data['title']['ar'], 'en' => 'Firas Al Majd | '.$data['title']['en']],
                'meta_description' => [
                    'ar' => 'شركة فراس المجد العمرانية — '.$data['title']['ar'].' خدمات المقاولات والتشطيبات والنقل والتوريد والLandscape والصيانة في الرياض',
                    'en' => 'Firas Al Majd Urban Company — '.$data['title']['en'].'. '.$data['short_description']['en'].' in Riyadh.',
                ],
            ]);
        }

        foreach ($services as $slug => $data) {
            $ids = Service::query()->whereIn('slug', $data['related'])->pluck('id', 'slug');
            $sync = [];
            foreach ($data['related'] as $i => $relatedSlug) {
                $sync[$ids[$relatedSlug]] = ['sort_order' => $i + 1];
            }
            Service::query()->where('slug', $slug)->first()->related()->sync($sync);
        }
    }
}
