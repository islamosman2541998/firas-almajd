<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /** Old service slugs merged into a current one (their projects are moved over before deletion). */
    protected array $merged = [
        'supply' => 'construction',
        'landscape' => 'construction',
    ];

    public function run(): void
    {
        $t = fn ($ar, $en) => ['ar' => $ar, 'en' => $en];

        $services = [
            'construction' => [
                'image' => 'seed/construction.webp',
                'title' => $t('المقاولات وأعمال الخرسانة', 'Contracting & concrete works'),
                'short_description' => $t('أعمال الخرسانة والفت أوت الكامل والحفر والأسفلت ونقل مواد المشاريع', 'Concrete, full fit-out, excavation, asphalt and project materials transport'),
                'scope' => [
                    $t('الخرسانات والفت أوت كامل', 'Concrete works & full fit-out'),
                    $t('الحفر والترحيل', 'Excavation & haulage'),
                    $t('الأسفلت', 'Asphalt works'),
                    $t('نقل مواد المشاريع: حجر ديكوري، بيس كورس، صبيز، كراش ساند', 'Project materials transport: decorative stone, base course, subbase & crushed sand'),
                    $t('اللاند سكيب', 'Landscape'),
                    $t('وكل ما يخص مجال المقاولات', 'All other contracting works'),
                ],
                'prep' => [$t('نوع المنشأة وموقعها', 'Building type & location'), $t('المخططات الإنشائية والكميات إن توفرت', 'Structural drawings & quantities, if available'), $t('حالة الموقع والأعمال المطلوبة', 'Site condition & required works')],
                'related' => ['maintenance', 'logistics', 'fitout'],
            ],
            'maintenance' => [
                'image' => 'seed/maintenance.webp',
                'title' => $t('أعمال الصيانة', 'Maintenance works'),
                'short_description' => $t('سباكة وكهرباء وعزل ومعالجات وتوريد مواد وتأجير معدات', 'Plumbing, electrical, waterproofing, repairs, materials supply and equipment rental'),
                'scope' => [
                    $t('أعمال السباكة', 'Plumbing works'),
                    $t('أعمال الكهرباء', 'Electrical works'),
                    $t('كشف تسربات المياه', 'Water leak detection'),
                    $t('الدهانات - يومي أو واجهات', 'Painting - daily or façades'),
                    $t('معالجة التشققات', 'Crack treatment'),
                    $t('معالجة الرطوبة', 'Damp treatment'),
                    $t('عزل الأسطح', 'Roof waterproofing'),
                    $t('عزل الخزانات', 'Tank waterproofing'),
                    $t('تركيب الأرضيات', 'Flooring installation'),
                    $t('تأجير المعدات', 'Equipment rental'),
                    $t('توريد مواد البناء', 'Building materials supply'),
                ],
                'prep' => [$t('نوع المبنى والمشكلة القائمة', 'Building type & current issue'), $t('صور أو وصف تفصيلي للحالة', 'Photos or a detailed description'), $t('موقع الأعمال وإمكانية المعاينة', 'Work location & inspection access')],
                'related' => ['construction', 'fitout', 'logistics'],
            ],
            'logistics' => [
                'image' => 'seed/logistics.webp',
                'title' => $t('أعمال النقليات', 'Transport works'),
                'short_description' => $t('نقل المياه للمشاريع وتريلات السطحات والقلابات وتناكر الفاير ترك', 'Project water transport, flatbed and tipper trailers, and fire truck tankers'),
                'scope' => [
                    $t('نقل المياه للمشاريع', 'Project water transport'),
                    $t('تريلات السطحات', 'Flatbed trailers'),
                    $t('تريلات القلابات', 'Tipper trailers'),
                    $t('تناكر الفاير ترك', 'Fire truck tankers'),
                ],
                'prep' => [$t('نوع الحمولة والكميات', 'Load type & quantities'), $t('موقع التحميل والتفريغ', 'Loading & unloading locations'), $t('مواعيد النقل وإمكانية دخول الموقع', 'Transport schedule & site access')],
                'related' => ['construction', 'maintenance', 'fitout'],
            ],
            'fitout' => [
                'image' => 'seed/cafe.webp',
                'title' => $t('تجهيز الكافيهات والديكور', 'Café fit-out & décor'),
                'short_description' => $t('تجهيز الكافيهات من الصفر حتى المفتاح والنجارة والديكورات والمطابخ', 'Turnkey café fit-outs, carpentry, décor and kitchens'),
                'scope' => [
                    $t('تجهيز الكافيهات من الصفر لين المفتاح', 'Café fit-out from scratch to turnkey'),
                    $t('نجارة الأخشاب والموبيليا', 'Woodwork & furniture carpentry'),
                    $t('الديكورات', 'Décor works'),
                    $t('تركيب المطابخ', 'Kitchen installation'),
                    $t('وغيرها', 'And more'),
                ],
                'prep' => [$t('مساحة المكان والاستخدام المطلوب', 'Space area & intended use'), $t('المخططات والتصور التصميمي إن توفرا', 'Drawings & design concept, if available'), $t('الخامات والتشطيبات المطلوبة', 'Required materials & finishes')],
                'related' => ['construction', 'maintenance', 'logistics'],
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
                    'ar' => 'شركة فراس المجد العمرانية — '.$data['title']['ar'].': '.$data['short_description']['ar'].' في الرياض',
                    'en' => 'Firas Al Majd Urban Company — '.$data['title']['en'].'. '.$data['short_description']['en'].' in Riyadh.',
                ],
            ]);
        }

        $this->removeOldServices(array_keys($services));

        foreach ($services as $slug => $data) {
            $ids = Service::query()->whereIn('slug', $data['related'])->pluck('id', 'slug');
            $sync = [];
            foreach ($data['related'] as $i => $relatedSlug) {
                $sync[$ids[$relatedSlug]] = ['sort_order' => $i + 1];
            }
            Service::query()->where('slug', $slug)->first()->related()->sync($sync);
        }

        $this->syncMenu();
    }

    /** Deletes services no longer offered, moving their projects to the service that replaced them. */
    protected function removeOldServices(array $keep): void
    {
        $ids = Service::query()->pluck('id', 'slug');

        Service::query()->whereNotIn('slug', $keep)->get()->each(function (Service $old) use ($ids) {
            if ($target = $ids[$this->merged[$old->slug] ?? ''] ?? null) {
                Project::query()->where('service_id', $old->id)->update(['service_id' => $target]);
            }

            $old->delete();
        });
    }

    /** Rebuilds the service links under every "Services" menu item so the menu matches the current services. */
    protected function syncMenu(): void
    {
        MenuItem::query()->where('type', 'service')->whereNotIn('linkable_id', Service::query()->pluck('id'))->delete();

        MenuItem::query()->where('route_name', 'services.index')->get()->each(function (MenuItem $parent) {
            MenuItem::query()->where('parent_id', $parent->id)->where('type', 'service')->delete();

            foreach (Service::query()->ordered()->get() as $j => $service) {
                MenuItem::create([
                    'location' => $parent->location,
                    'parent_id' => $parent->id,
                    'title' => $service->getTranslations('title'),
                    'type' => 'service',
                    'linkable_type' => 'service',
                    'linkable_id' => $service->id,
                    'is_active' => true,
                    'sort_order' => $j + 1,
                ]);
            }
        });
    }
}
