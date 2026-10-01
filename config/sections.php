<?php

/*
|--------------------------------------------------------------------------
| Page sections
|--------------------------------------------------------------------------
| "types" describe every section template: its Blade view and editable
| fields (with the design's default content). "pages" list the sections
| each static page renders, in their default order. Anything saved from
| the dashboard overrides these defaults (see App\Services\SectionService).
|
| Field keys: type (text|textarea|image|link|toggle|number|select|color|repeater),
| t (translatable ar/en), label [ar, en], default, options, fields (repeater).
*/

$t = fn (string $ar, string $en) => ['ar' => $ar, 'en' => $en];
$l = fn (string $ar, string $en) => ['ar' => $ar, 'en' => $en];

return [

    'types' => [

        'hero' => [
            'label' => $l('السلايدر الرئيسي', 'Hero slider'),
            'view' => 'site.sections.hero',
            'note' => $l('الشرائح نفسها تُدار من قسم السلايدر.', 'Slides are managed from the Slider module.'),
            'fields' => [
                'autoplay' => ['type' => 'toggle', 'label' => $l('تشغيل تلقائي', 'Autoplay'), 'default' => true],
                'delay' => ['type' => 'number', 'label' => $l('مدة الشريحة (ملي ثانية)', 'Slide duration (ms)'), 'default' => 6000],
                'effect' => ['type' => 'select', 'label' => $l('تأثير الانتقال', 'Transition'), 'default' => 'fade', 'options' => ['fade' => $l('تلاشي', 'Fade'), 'slide' => $l('انزلاق', 'Slide')]],
                'loop' => ['type' => 'toggle', 'label' => $l('تكرار', 'Loop'), 'default' => true],
                'arrows' => ['type' => 'toggle', 'label' => $l('إظهار الأسهم', 'Show arrows'), 'default' => true],
                'pagination' => ['type' => 'toggle', 'label' => $l('إظهار النقاط', 'Show dots'), 'default' => true],
            ],
        ],

        'about' => [
            'label' => $l('من نحن', 'About'),
            'view' => 'site.sections.about',
            'fields' => [
                'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t('من نحن', 'WHO WE ARE')],
                'lead' => ['type' => 'textarea', 't' => true, 'label' => $l('النص الرئيسي', 'Lead'), 'default' => $t('في شركة فراس المجد، كل مشروع مسؤولية وكل تفصيلة تستحق الإتقان', 'At Firas Al Majd, every project is a responsibility. Every detail is an opportunity to build well.')],
                'body' => ['type' => 'textarea', 't' => true, 'label' => $l('النص', 'Body'), 'default' => $t('نجمع المقاولات والتشطيبات والبنية التحتية وخدمات الموقع في نطاق عمل واحد', 'Firas Al Majd Urban Company brings together general contracting, fit-outs and infrastructure, alongside transport, material supply and landscaping. We connect the needs of your project through one coordinated workflow, from site preparation to handover.')],
                'principles' => ['type' => 'repeater', 'label' => $l('المبادئ', 'Principles'), 'fields' => [
                    'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title')],
                    'text' => ['type' => 'text', 't' => true, 'label' => $l('النص', 'Text')],
                ], 'default' => [
                    ['title' => $t('جودة في التنفيذ', 'Quality in execution'), 'text' => $t('مواد مناسبة وتنفيذ دقيق', 'Considered materials. Careful details.')],
                    ['title' => $t('التزام في كل مرحلة', 'Committed at every stage'), 'text' => $t('تنسيق واضح ومتابعة مستمرة', 'Clear coordination. Consistent follow-up.')],
                ]],
                'image' => ['type' => 'image', 'label' => $l('الصورة', 'Image'), 'default' => 'seed/courtyard.webp'],
                'image_alt' => ['type' => 'text', 't' => true, 'label' => $l('النص البديل للصورة', 'Image alt text'), 'default' => $t('فناء معماري معاصر بتنسيق نباتي — صورة تعبيرية', 'Contemporary landscaped courtyard — illustrative concept')],
                'caption' => ['type' => 'text', 't' => true, 'label' => $l('تعليق الصورة', 'Image caption'), 'default' => $t('التفاصيل تصنع الفرق', 'The difference is in the details.')],
            ],
        ],

        'disciplines' => [
            'label' => $l('مجالات العمل (كروت الخدمات)', 'Disciplines (service cards)'),
            'view' => 'site.sections.disciplines',
            'note' => $l('الكروت تُعرض من قسم الخدمات.', 'Cards come from the Services module.'),
            'fields' => [
                'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t('مجالات عملنا', 'OUR DISCIPLINES')],
                'subtitle' => ['type' => 'text', 't' => true, 'label' => $l('العنوان الفرعي', 'Subtitle'), 'default' => $t('تخصصات عدة ورؤية واحدة', 'Multiple disciplines. One vision')],
                'home_only' => ['type' => 'toggle', 'label' => $l('عرض الخدمات المحددة للرئيسية فقط', 'Only services marked for home'), 'default' => false],
                'limit' => ['type' => 'number', 'label' => $l('أقصى عدد (0 = الكل)', 'Max cards (0 = all)'), 'default' => 0],
                'show_link' => ['type' => 'toggle', 'label' => $l('إظهار الرابط', 'Show link'), 'default' => true],
                'link_text' => ['type' => 'text', 't' => true, 'label' => $l('نص الرابط', 'Link text'), 'default' => $t('جميع الخدمات بالتفصيل', 'All services in detail')],
                'link_url' => ['type' => 'link', 'label' => $l('الرابط', 'Link'), 'default' => 'route:services'],
            ],
        ],

        'partners' => [
            'label' => $l('شركاء النجاح', 'Partners'),
            'view' => 'site.sections.partners',
            'note' => $l('الشعارات تُدار من قسم الشركاء.', 'Logos are managed from the Partners module.'),
            'fields' => [
                'kicker' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Kicker'), 'default' => $t('OUR PARTNERS', 'OUR PARTNERS')],
                'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t('شركاء النجاح', 'Our partners')],
                'subtitle' => ['type' => 'text', 't' => true, 'label' => $l('العنوان الفرعي', 'Subtitle'), 'default' => $t('معًا نبني علاقات تدوم', 'Building lasting relationships, together')],
                'autoplay' => ['type' => 'toggle', 'label' => $l('تشغيل تلقائي', 'Autoplay'), 'default' => true],
                'delay' => ['type' => 'number', 'label' => $l('المدة (ملي ثانية)', 'Interval (ms)'), 'default' => 3500],
            ],
        ],

        'contact' => [
            'label' => $l('التواصل', 'Contact'),
            'view' => 'site.sections.contact',
            'note' => $l('أرقام الهاتف والبريد والعنوان من الإعدادات العامة.', 'Phone, email and address come from General settings.'),
            'fields' => [
                'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t('لنبنِ شيئًا يستحق', 'LET’S BUILD SOMETHING THAT MATTERS')],
                'intro' => ['type' => 'textarea', 't' => true, 'label' => $l('المقدمة', 'Intro'), 'default' => $t('شاركنا احتياج مشروعك ونحدد معك الخطوة التالية', 'Tell us what you have in mind. We’ll discuss the details and the services that suit your project.')],
                'phone_label' => ['type' => 'text', 't' => true, 'label' => $l('عنوان الهاتف', 'Phone label'), 'default' => $t('اتصل بنا', 'CALL US')],
                'email_label' => ['type' => 'text', 't' => true, 'label' => $l('عنوان البريد', 'Email label'), 'default' => $t('البريد الإلكتروني', 'EMAIL')],
                'cr_label' => ['type' => 'text', 't' => true, 'label' => $l('عنوان السجل التجاري', 'CR label'), 'default' => $t('رقم السجل التجاري', 'Commercial registration number')],
                'po_box_label' => ['type' => 'text', 't' => true, 'label' => $l('عنوان صندوق البريد', 'P.O. Box label'), 'default' => $t('P.O.Box', 'P.O. Box')],
                'address_label' => ['type' => 'text', 't' => true, 'label' => $l('عنوان المقر', 'Address label'), 'default' => $t('مقرّنا', 'OUR OFFICE')],
                'side' => ['type' => 'select', 'label' => $l('العمود الثاني', 'Second column'), 'default' => 'map', 'options' => ['map' => $l('الخريطة', 'Map'), 'form' => $l('نموذج التواصل', 'Contact form')]],
                'map_link_text' => ['type' => 'text', 't' => true, 'label' => $l('نص رابط الخريطة', 'Map link text'), 'default' => $t('عرض المنطقة على Google Maps', 'View the area on Google Maps')],
                'form_title' => ['type' => 'text', 't' => true, 'label' => $l('عنوان النموذج', 'Form title'), 'default' => $t('حدثنا عن مشروعك', 'Tell us about your project')],
                'name_label' => ['type' => 'text', 't' => true, 'label' => $l('حقل الاسم', 'Name label'), 'default' => $t('الاسم', 'Your name')],
                'name_placeholder' => ['type' => 'text', 't' => true, 'label' => $l('مثال الاسم', 'Name placeholder'), 'default' => $t('اسمك الكريم', 'Full name')],
                'mobile_label' => ['type' => 'text', 't' => true, 'label' => $l('حقل الجوال', 'Mobile label'), 'default' => $t('رقم الجوال', 'Mobile number')],
                'form_email_label' => ['type' => 'text', 't' => true, 'label' => $l('حقل البريد', 'Email field label'), 'default' => $t('البريد الإلكتروني', 'Email address')],
                'service_label' => ['type' => 'text', 't' => true, 'label' => $l('حقل الخدمة', 'Service label'), 'default' => $t('الخدمة المطلوبة', 'Required service')],
                'service_placeholder' => ['type' => 'text', 't' => true, 'label' => $l('اختيار الخدمة', 'Service placeholder'), 'default' => $t('اختر الخدمة المناسبة', 'Choose a service')],
                'message_label' => ['type' => 'text', 't' => true, 'label' => $l('حقل الرسالة', 'Message label'), 'default' => $t('نبذة عن المشروع', 'Project brief')],
                'message_placeholder' => ['type' => 'text', 't' => true, 'label' => $l('مثال الرسالة', 'Message placeholder'), 'default' => $t('نوع المشروع، الموقع، وأي تفاصيل تهمّك', 'Project type, location and the details that matter to you…')],
                'form_note' => ['type' => 'text', 't' => true, 'label' => $l('ملاحظة النموذج', 'Form note'), 'default' => $t('راجع رسالتك وأرسلها عبر WhatsApp', 'Prepare your message, then send it yourself through WhatsApp.')],
                'submit_text' => ['type' => 'text', 't' => true, 'label' => $l('زر الإرسال', 'Submit button'), 'default' => $t('متابعة عبر WhatsApp', 'Continue to WhatsApp')],
                'success_text' => ['type' => 'text', 't' => true, 'label' => $l('رسالة النجاح', 'Success message'), 'default' => $t('رسالتك جاهزة راجعها وأرسلها في WhatsApp', 'Your message is ready. Review and send it in WhatsApp.')],
                'fallback_text' => ['type' => 'text', 't' => true, 'label' => $l('رابط WhatsApp البديل', 'WhatsApp fallback link'), 'default' => $t('افتح الرسالة في WhatsApp', 'Open your message in WhatsApp')],
                'open_whatsapp' => ['type' => 'toggle', 'label' => $l('فتح WhatsApp بعد الإرسال', 'Open WhatsApp after submit'), 'default' => true],
            ],
        ],

        'services_accordion' => [
            'label' => $l('تفاصيل الخدمات (قائمة منسدلة)', 'Services accordion'),
            'view' => 'site.sections.services-accordion',
            'fields' => [
                'eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Eyebrow'), 'default' => $t('خبراتنا في خدمتك', 'OUR EXPERTISE')],
                'title_line1' => ['type' => 'text', 't' => true, 'label' => $l('العنوان - السطر الأول', 'Title line 1'), 'default' => $t('طموحك كبير', 'Ambitious visions.')],
                'title_line2' => ['type' => 'text', 't' => true, 'label' => $l('العنوان - السطر الثاني', 'Title line 2'), 'default' => $t('وحلولنا متكاملة', 'Integrated solutions.')],
                'intro' => ['type' => 'textarea', 't' => true, 'label' => $l('المقدمة', 'Intro'), 'default' => $t('تخصصات البناء وخدمات الموقع تحت مظلة واحدة', 'From the groundworks to the finishing touches, we bring the disciplines your project needs together.')],
            ],
        ],

        'faq' => [
            'label' => $l('الأسئلة الشائعة', 'FAQ'),
            'view' => 'site.sections.faq',
            'note' => $l('الأسئلة تُدار من قسم الأسئلة الشائعة.', 'Questions are managed from the FAQ module.'),
            'fields' => [
                'eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Eyebrow'), 'default' => $t('قبل أن نبدأ', 'BEFORE WE BEGIN')],
                'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t('تفاصيل تهمّك', 'A few useful details.')],
                'intro' => ['type' => 'textarea', 't' => true, 'label' => $l('المقدمة', 'Intro'), 'default' => $t('إجابات مختصرة على أسئلة البداية ولتفاصيل مشروعك، يسعدنا أن نتحدث معك', 'A few answers to get started. For your project’s details, we’d be happy to talk.')],
            ],
        ],

        'cta' => [
            'label' => $l('شريط الدعوة للتواصل', 'Call to action'),
            'view' => 'site.sections.cta',
            'fields' => [
                'title' => ['type' => 'textarea', 't' => true, 'label' => $l('العنوان (سطر جديد = فاصل)', 'Title (new line = break)'), 'default' => $t("لديك مشروع في ذهنك؟\nلنضع له بداية واضحة", "Have a project in mind?\nLet’s give it a clear beginning.")],
                'button_text' => ['type' => 'text', 't' => true, 'label' => $l('نص الزر', 'Button text'), 'default' => $t('تحدث معنا عن مشروعك', 'Tell us about your project')],
                'button_url' => ['type' => 'link', 'label' => $l('رابط الزر', 'Button link'), 'default' => 'route:contact'],
            ],
        ],

        'profile_cta' => [
            'label' => $l('الملف التعريفي', 'Company profile'),
            'view' => 'site.sections.profile-cta',
            'note' => $l('اترك الرابط فارغًا لفتح صفحة "company-profile" تلقائيًا.', 'Leave the link empty to open the "company-profile" page automatically.'),
            'fields' => [
                'eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Eyebrow'), 'default' => $t('تعرّف علينا أكثر', 'GET TO KNOW US')],
                'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t('الملف التعريفي لشركة فراس المجد', 'Firas Al Majd company profile')],
                'text' => ['type' => 'textarea', 't' => true, 'label' => $l('النص', 'Text'), 'default' => $t('خدماتنا وأعمالنا وشهاداتنا في ملف واحد، تصفّحه أو حمّله بسهولة', 'Our services, projects and certifications in one file to browse or download')],
                'button_text' => ['type' => 'text', 't' => true, 'label' => $l('نص الزر', 'Button text'), 'default' => $t('الملف التعريفي', 'Company profile')],
                'button_url' => ['type' => 'link', 'label' => $l('رابط الزر', 'Button link'), 'default' => ''],
            ],
        ],

        'charter' => [
            'label' => $l('ما الذي يوجّه عملنا', 'What guides our work'),
            'view' => 'site.sections.charter',
            'fields' => [
                'eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Eyebrow'), 'default' => $t('ما الذي يوجّه عملنا؟', 'WHAT GUIDES OUR WORK')],
                'title' => ['type' => 'textarea', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t("نفهم الصورة الكبيرة\nونعتني بأصغر التفاصيل", "We understand the big picture.\nAnd care about the smallest detail.")],
                'items' => ['type' => 'repeater', 'label' => $l('العناصر', 'Items'), 'fields' => [
                    'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title')],
                    'text' => ['type' => 'textarea', 't' => true, 'label' => $l('النص', 'Text')],
                ], 'default' => [
                    ['title' => $t('رؤية مترابطة', 'A connected view'), 'text' => $t('ننظر إلى علاقة كل تخصص بالآخر، من أعمال الأرض إلى التشطيبات والمرافق', 'We consider how each discipline connects, from groundworks to finishes and facilities.')],
                    ['title' => $t('وضوح في النطاق', 'Clarity of scope'), 'text' => $t('نبدأ بمناقشة المتطلبات والمواصفات والأولويات قبل تحديد أعمال التنفيذ', 'We start by discussing requirements, specifications and priorities before defining the work.')],
                    ['title' => $t('عناية في التنفيذ', 'Care in execution'), 'text' => $t('نهتم بالتفاصيل وتنسيق الموقع، مع مراعاة الجودة والسلامة واحتياجات المتابعة', 'We care about details and site coordination, considering quality, safety and follow-up needs.')],
                ]],
            ],
        ],

        'sectors' => [
            'label' => $l('أين تتكامل خدماتنا', 'Where our services connect'),
            'view' => 'site.sections.sectors',
            'fields' => [
                'eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Eyebrow'), 'default' => $t('أين تتكامل خدماتنا', 'WHERE OUR SERVICES CONNECT')],
                'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t('لكل مساحة، متطلباتها', 'Every space has its own needs.')],
                'items' => ['type' => 'repeater', 'label' => $l('العناصر', 'Items'), 'fields' => [
                    'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title')],
                    'text' => ['type' => 'textarea', 't' => true, 'label' => $l('النص', 'Text')],
                ], 'default' => [
                    ['title' => $t('المساحات السكنية', 'Residential spaces'), 'text' => $t('أعمال إنشاء وتشطيب وصيانة للمباني ومرافقها', 'Construction, finishes and maintenance for buildings and their facilities.')],
                    ['title' => $t('المساحات التجارية', 'Commercial spaces'), 'text' => $t('تجهيز المقاهي، التشطيبات، النجارة والأعمال الكهروميكانيكية', 'Café fit-outs, finishes, joinery and MEP works.')],
                    ['title' => $t('المواقع والمساحات الخارجية', 'Sites & outdoor spaces'), 'text' => $t('تجهيز الأرض، النقل، تنسيق المواقع وشبكات الري', 'Ground preparation, transport, landscaping and irrigation.')],
                ]],
            ],
        ],

        'method' => [
            'label' => $l('مراحل العمل (رؤيتنا)', 'Method (our vision)'),
            'view' => 'site.sections.method',
            'fields' => [
                'eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Eyebrow'), 'default' => $t('رؤيتنا', 'OUR VISION')],
                'title' => ['type' => 'textarea', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t("من الفكرة\nإلى التنفيذ", "From the idea\nto execution.")],
                'image' => ['type' => 'image', 'label' => $l('الصورة', 'Image'), 'default' => 'seed/construction.webp'],
                'image_alt' => ['type' => 'text', 't' => true, 'label' => $l('النص البديل', 'Image alt'), 'default' => $t('أعمال إنشاء — صورة تعبيرية', 'Construction works — illustrative image')],
                'items' => ['type' => 'repeater', 'label' => $l('المراحل', 'Stages'), 'fields' => [
                    'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title')],
                    'text' => ['type' => 'textarea', 't' => true, 'label' => $l('النص', 'Text')],
                    'tags' => ['type' => 'text', 't' => true, 'label' => $l('الوسوم', 'Tags')],
                ], 'default' => [
                    ['title' => $t('نفهم قبل أن نبدأ', 'Understand before starting'), 'text' => $t('نحدد نوع المشروع والموقع والأولويات', 'We define the project type, location and priorities'), 'tags' => $t('بيانات الموقع · نطاق أولي · متطلبات الاستخدام', 'Site information · Initial scope · Intended use')],
                    ['title' => $t('نحوّل المتطلبات إلى خطة', 'Turn requirements into a plan'), 'text' => $t('نراجع المخططات والمواد وتسلسل التنفيذ', 'We review drawings, materials and work sequencing'), 'tags' => $t('مواصفات واضحة · تنسيق تخصصات · جدول يناسب النطاق', 'Clear specifications · Discipline coordination · Scope-based schedule')],
                    ['title' => $t('نربط بين الفرق والموقع', 'Connect teams and site'), 'text' => $t('ننسّق الأعمال والفرق ونتابع الجودة', 'We coordinate the work and teams while monitoring quality'), 'tags' => $t('متابعة أعمال · تنسيق توريد · مراجعة التفاصيل', 'Work follow-up · Supply coordination · Detail review')],
                    ['title' => $t('نراجع ثم نسلّم', 'Review, then hand over'), 'text' => $t('نراجع الأعمال ونرتب التسليم والمتابعة', 'We review the work and arrange handover and follow-up'), 'tags' => $t('مراجعة نطاق الأعمال · ملاحظات التسليم · احتياجات المتابعة', 'Scope review · Handover observations · Follow-up needs')],
                ]],
            ],
        ],

        'process' => [
            'label' => $l('كل خطوة محسوبة', 'Process steps'),
            'view' => 'site.sections.process',
            'fields' => [
                'eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Eyebrow'), 'default' => $t('رؤية واضحة', 'A CLEAR VISION')],
                'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t('كل خطوة، محسوبة', 'Every step, considered.')],
                'intro' => ['type' => 'textarea', 't' => true, 'label' => $l('المقدمة', 'Intro'), 'default' => $t('نفهم مشروعك وننسّق مراحله بوضوح', 'Good construction starts with understanding. We work with you through clear stages, keeping the full picture in view.')],
                'steps' => ['type' => 'repeater', 'label' => $l('الخطوات', 'Steps'), 'fields' => [
                    'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title')],
                    'text' => ['type' => 'textarea', 't' => true, 'label' => $l('النص', 'Text')],
                ], 'default' => [
                    ['title' => $t('نستمع ونفهم', 'Listen & understand'), 'text' => $t('نحدد احتياجات الموقع ونطاق العمل', 'We discuss your vision and site requirements to define the scope and priorities.')],
                    ['title' => $t('نخطط بدقة', 'Plan with precision'), 'text' => $t('نحدد التنفيذ والمواد والجدول', 'We outline the execution, materials and schedule around your project’s requirements.')],
                    ['title' => $t('ننفّذ ونتابع', 'Build & coordinate'), 'text' => $t('ننسّق الفرق والتوريد ونتابع الجودة', 'We coordinate teams and supply, with attention to quality and safety on site.')],
                    ['title' => $t('نسلّم بعناية', 'Deliver with care'), 'text' => $t('نراجع الأعمال ونرتب التسليم', 'We review the work with you and coordinate maintenance and follow-up needs.')],
                ]],
                'end_text' => ['type' => 'text', 't' => true, 'label' => $l('النص الختامي', 'Closing text'), 'default' => $t('تعرّف على ما تتضمنه كل مرحلة', 'Explore what each stage involves.')],
                'link_text' => ['type' => 'text', 't' => true, 'label' => $l('نص الرابط', 'Link text'), 'default' => $t('رؤيتنا بالتفصيل', 'Our vision in detail')],
                'link_url' => ['type' => 'link', 'label' => $l('الرابط', 'Link'), 'default' => 'route:approach'],
            ],
        ],

        'office' => [
            'label' => $l('طرق التواصل', 'Reach us'),
            'view' => 'site.sections.office',
            'fields' => [
                'eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Eyebrow'), 'default' => $t('تواصل بالطريقة الأنسب لك', 'REACH US YOUR WAY')],
                'title' => ['type' => 'textarea', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t("الرياض\nهنا نبدأ الحديث", "Riyadh.\nLet’s start here.")],
                'text' => ['type' => 'textarea', 't' => true, 'label' => $l('النص', 'Text'), 'default' => $t('حي الملك عبدالعزيز، شارع ابن كثير، الرياض 12233، المملكة العربية السعودية', 'King Abdulaziz District, Ibn Katheer Street, Riyadh 12233, Saudi Arabia.')],
                'map_link_text' => ['type' => 'text', 't' => true, 'label' => $l('نص رابط الخريطة', 'Map link text'), 'default' => $t('عرض المنطقة على الخريطة', 'View the area on a map')],
                'call_title' => ['type' => 'text', 't' => true, 'label' => $l('عنوان الاتصال', 'Call title'), 'default' => $t('مكالمة مباشرة', 'A direct conversation')],
                'email_title' => ['type' => 'text', 't' => true, 'label' => $l('عنوان البريد', 'Email title'), 'default' => $t('المخططات والتفاصيل', 'Drawings & details')],
                'whatsapp_title' => ['type' => 'text', 't' => true, 'label' => $l('عنوان WhatsApp', 'WhatsApp title'), 'default' => $t('ابدأ عبر WhatsApp', 'Start on WhatsApp')],
                'whatsapp_text' => ['type' => 'text', 't' => true, 'label' => $l('نص WhatsApp', 'WhatsApp text'), 'default' => $t('تواصل مع فريقنا', 'Talk to our team')],
            ],
        ],

        'projects' => [
            'label' => $l('المشاريع', 'Projects'),
            'view' => 'site.sections.projects',
            'note' => $l('المشاريع تُدار من قسم المشاريع.', 'Projects are managed from the Projects module.'),
            'fields' => [
                'eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Eyebrow'), 'default' => $t('أعمال مختارة', 'SELECTED WORK')],
                'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t('تفاصيل تصنع الفرق', 'Details make the difference')],
            ],
        ],

        'gallery' => [
            'label' => $l('معرض الصور', 'Gallery'),
            'view' => 'site.sections.gallery',
            'note' => $l('الصور تُدار من قسم معرض الصور.', 'Images are managed from the Gallery module.'),
            'fields' => [],
        ],

        'certificates' => [
            'label' => $l('الشهادات', 'Certificates'),
            'view' => 'site.sections.certificates',
            'note' => $l('الشهادات تُدار من قسم الشهادات.', 'Certificates are managed from the Certificates module.'),
            'fields' => [
                'view_text' => ['type' => 'text', 't' => true, 'label' => $l('نص عرض الشهادة', 'View text'), 'default' => $t('عرض الشهادة', 'View certificate')],
            ],
        ],

        'careers' => [
            'label' => $l('الوظائف ونموذج التقديم', 'Careers & application form'),
            'view' => 'site.sections.careers',
            'note' => $l('الوظائف تُدار من قسم الوظائف.', 'Jobs are managed from the Careers module.'),
            'fields' => [
                'eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Eyebrow'), 'default' => $t('الفرص المتاحة', 'OPEN ROLES')],
                'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t('اختر الفرصة المناسبة', 'Choose your next opportunity')],
                'apply_text' => ['type' => 'text', 't' => true, 'label' => $l('زر التقديم', 'Apply button'), 'default' => $t('قدّم الآن', 'Apply now')],
                'empty_text' => ['type' => 'text', 't' => true, 'label' => $l('نص عدم وجود وظائف', 'No jobs text'), 'default' => $t('لا توجد فرص متاحة حاليًا', 'There are no open roles right now')],
                'form_eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير للنموذج', 'Form eyebrow'), 'default' => $t('طلب التوظيف', 'APPLICATION')],
                'form_title' => ['type' => 'text', 't' => true, 'label' => $l('عنوان النموذج', 'Form title'), 'default' => $t('سجّل بياناتك', 'Tell us about yourself')],
                'form_intro' => ['type' => 'textarea', 't' => true, 'label' => $l('مقدمة النموذج', 'Form intro'), 'default' => $t('سنراجع بياناتك ونتواصل عند توافق الخبرة مع الوظيفة', 'We will review your details and contact matching candidates')],
                'name_label' => ['type' => 'text', 't' => true, 'label' => $l('حقل الاسم', 'Name label'), 'default' => $t('الاسم الكامل', 'Full name')],
                'phone_label' => ['type' => 'text', 't' => true, 'label' => $l('حقل الجوال', 'Mobile label'), 'default' => $t('رقم الجوال', 'Mobile number')],
                'email_label' => ['type' => 'text', 't' => true, 'label' => $l('حقل البريد', 'Email label'), 'default' => $t('البريد الإلكتروني', 'Email')],
                'job_label' => ['type' => 'text', 't' => true, 'label' => $l('حقل الوظيفة', 'Position label'), 'default' => $t('الوظيفة', 'Position')],
                'experience_label' => ['type' => 'text', 't' => true, 'label' => $l('حقل الخبرة', 'Experience label'), 'default' => $t('سنوات الخبرة', 'Years of experience')],
                'summary_label' => ['type' => 'text', 't' => true, 'label' => $l('حقل النبذة', 'Profile label'), 'default' => $t('نبذة مختصرة', 'Short profile')],
                'submit_text' => ['type' => 'text', 't' => true, 'label' => $l('زر الإرسال', 'Submit button'), 'default' => $t('إرسال الطلب عبر WhatsApp', 'Send application via WhatsApp')],
                'success_text' => ['type' => 'text', 't' => true, 'label' => $l('رسالة النجاح', 'Success message'), 'default' => $t('طلبك جاهز للإرسال عبر WhatsApp', 'Your application is ready to send via WhatsApp')],
                'open_whatsapp' => ['type' => 'toggle', 'label' => $l('فتح WhatsApp بعد الإرسال', 'Open WhatsApp after submit'), 'default' => true],
            ],
        ],

        'service_scope' => [
            'label' => $l('نطاق الخدمة', 'Service scope'),
            'view' => 'site.sections.service-scope',
            'note' => $l('بنود النطاق من صفحة الخدمة نفسها.', 'Scope items come from each service.'),
            'fields' => [
                'eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Eyebrow'), 'default' => $t('نطاق الخدمة', 'SERVICE SCOPE')],
                'title' => ['type' => 'textarea', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t("أعمال متكاملة\nوتفاصيل واضحة", "A connected scope.\nClear details.")],
            ],
        ],

        'service_gallery' => [
            'label' => $l('معرض الخدمة', 'Service gallery'),
            'view' => 'site.sections.service-gallery',
            'note' => $l('الصور والفيديوهات تُضاف من تبويب المعرض في صفحة كل خدمة.', 'Images and videos are added from the Gallery tab of each service.'),
            'fields' => [
                'eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Eyebrow'), 'default' => $t('من أعمالنا', 'FROM OUR WORK')],
                'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t('معرض الخدمة', 'Service gallery')],
            ],
        ],

        'service_related' => [
            'label' => $l('خدمات مرتبطة', 'Related services'),
            'view' => 'site.sections.service-related',
            'fields' => [
                'eyebrow' => ['type' => 'text', 't' => true, 'label' => $l('النص الصغير', 'Eyebrow'), 'default' => $t('قد يحتاج مشروعك أيضًا', 'YOUR PROJECT MAY ALSO NEED')],
                'title' => ['type' => 'text', 't' => true, 'label' => $l('العنوان', 'Title'), 'default' => $t('خدمات تكمل الصورة', 'Services that complete the picture.')],
                'back_text' => ['type' => 'text', 't' => true, 'label' => $l('نص العودة', 'Back link text'), 'default' => $t('العودة لجميع الخدمات', 'Back to all services')],
            ],
        ],

        'page_gallery' => [
            'label' => $l('المعرض في الصفحات', 'Page gallery'),
            'view' => 'site.sections.page-gallery',
            'fields' => [
                'gallery_title' => ['type' => 'text', 't' => true, 'label' => $l('عنوان المعرض', 'Gallery title'), 'default' => $t('المعرض', 'Gallery')],
                'files_title' => ['type' => 'text', 't' => true, 'label' => $l('عنوان الملفات', 'Files title'), 'default' => $t('الملفات', 'Files')],
                'open_file' => ['type' => 'text', 't' => true, 'label' => $l('نص فتح الملف', 'Open file text'), 'default' => $t('عرض الملف', 'View file')],
                'download_file' => ['type' => 'text', 't' => true, 'label' => $l('نص التحميل', 'Download text'), 'default' => $t('تحميل', 'Download')],
            ],
        ],
    ],

    /*
    | Static pages. "sections" maps instance key => type (or [type, defaults]).
    */
    'pages' => [
        'home' => ['label' => $l('الرئيسية', 'Home'), 'route' => 'home', 'sections' => [
            'hero' => 'hero',
            'about' => 'about',
            'disciplines' => 'disciplines',
            'partners' => 'partners',
            'profile' => 'profile_cta',
            'contact' => 'contact',
        ]],
        'about' => ['label' => $l('من نحن', 'About us'), 'route' => 'about', 'sections' => [
            'about' => 'about',
            'charter' => 'charter',
            'sectors' => 'sectors',
            'cta' => 'cta',
        ]],
        'services' => ['label' => $l('الخدمات', 'Services'), 'route' => 'services.index', 'sections' => [
            'disciplines' => ['disciplines', ['link_text' => $t('نطاق الخدمات بالتفصيل', 'Explore each service scope'), 'link_url' => '#services']],
            'accordion' => 'services_accordion',
            'faq' => 'faq',
            'cta' => 'cta',
        ]],
        'service' => ['label' => $l('صفحة الخدمة', 'Service page'), 'route' => null, 'sections' => [
            'scope' => 'service_scope',
            'gallery' => 'service_gallery',
            'related' => 'service_related',
            'cta' => 'cta',
        ]],
        'projects' => ['label' => $l('المشاريع', 'Projects'), 'route' => 'projects', 'sections' => [
            'projects' => 'projects',
            'cta' => 'cta',
        ]],
        'gallery' => ['label' => $l('معرض الصور', 'Gallery'), 'route' => 'gallery', 'sections' => [
            'gallery' => 'gallery',
        ]],
        'certificates' => ['label' => $l('الشهادات المعتمدة', 'Certifications'), 'route' => 'certificates', 'sections' => [
            'certificates' => 'certificates',
        ]],
        'careers' => ['label' => $l('الوظائف', 'Careers'), 'route' => 'careers', 'sections' => [
            'careers' => 'careers',
        ]],
        'approach' => ['label' => $l('رؤيتنا', 'Our vision'), 'route' => 'approach', 'sections' => [
            'method' => 'method',
            'process' => 'process',
            'cta' => 'cta',
        ]],
        'contact' => ['label' => $l('تواصل معنا', 'Contact us'), 'route' => 'contact', 'sections' => [
            'contact' => ['contact', ['side' => 'form']],
            'office' => 'office',
            'faq' => 'faq',
        ]],
        'page' => ['label' => $l('الصفحات المخصصة', 'Custom pages'), 'route' => null, 'sections' => [
            'gallery' => 'page_gallery',
        ]],
    ],
];
