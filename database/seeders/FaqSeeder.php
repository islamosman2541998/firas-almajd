<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        Faq::query()->delete();

        $faqs = [
            [['هل يمكن تنفيذ أكثر من خدمة في نفس المشروع؟', 'Can one project include several services?'], ['نعم، يمكن مناقشة نطاق متكامل يشمل الإنشاء والتشطيبات والتوريد والنقل وتنسيق الموقع، أو اختيار خدمة مستقلة حسب احتياجك', 'Yes. We can discuss an integrated scope spanning construction, fit-out, supplies, transport and landscaping, or a single service suited to your needs.']],
            [['ما المعلومات المطلوبة لمناقشة المشروع؟', 'What information helps us discuss your project?'], ['ابدأ بنوع المشروع وموقعه ونطاق الأعمال المطلوب المخططات والكميات والمواصفات، إن توفرت، تساعد على مناقشة المتطلبات بشكل أدق', 'Start with the project type, location and required scope. Drawings, quantities and specifications, when available, help us discuss the requirements more precisely.']],
            [['هل تقدمون تجهيز المقاهي من الصفر؟', 'Do you provide complete café fit-outs?'], ['تشمل خدماتنا تجهيز المقاهي من الصفر حتى تسليم المفتاح، مع أعمال التشطيبات والنجارة والديكور والأرضيات والأعمال المرتبطة بها وفق النطاق المتفق عليه', 'Our services include turnkey café fit-outs, with finishes, joinery, interiors, flooring and related works within the agreed scope.']],
            [['هل تشمل خدمات الLandscape شبكات الري؟', 'Does landscaping include irrigation?'], ['نعم، تشمل الزراعة وتوريد النباتات والنخيل، وتمديد شبكات الري والمحابس والصمامات وأنظمة التحكم، والصيانة الزراعية والمتابعة', 'Yes. Services cover planting, plants and palms, irrigation networks, valves, controls, landscape maintenance and follow-up.']],
            [['كيف أطلب مناقشة أو عرضًا للمشروع؟', 'How can I request a project discussion or quotation?'], ['املأ نموذج التواصل لتجهيز رسالة WhatsApp، أو تواصل معنا هاتفيًا أو بالبريد الإلكتروني نراجع تفاصيل الطلب معك لتحديد الخطوة التالية', 'Use the contact form to prepare a WhatsApp message, or reach us by phone or email. We review the details with you to identify the next step.']],
        ];

        foreach ($faqs as $i => [$q, $a]) {
            Faq::create(['question' => ['ar' => $q[0], 'en' => $q[1]], 'answer' => ['ar' => $a[0], 'en' => $a[1]], 'is_active' => true, 'sort_order' => $i + 1]);
        }
    }
}
