<?php

return [

    'locales' => [
        'ar' => ['name' => 'العربية', 'short' => 'عربي', 'dir' => 'rtl', 'og' => 'ar_SA'],
        'en' => ['name' => 'English', 'short' => 'EN', 'dir' => 'ltr', 'og' => 'en_US'],
    ],

    'default_locale' => env('APP_LOCALE', 'ar'),

    // Static routes selectable in menus and link fields (route name => page key).
    'routes' => [
        'home' => 'home',
        'about' => 'about',
        'services.index' => 'services',
        'projects' => 'projects',
        'gallery' => 'gallery',
        'certificates' => 'certificates',
        'careers' => 'careers',
        'approach' => 'approach',
        'contact' => 'contact',
    ],

    'social_platforms' => [
        'facebook' => ['label' => 'Facebook', 'icon' => 'fa-facebook-f'],
        'instagram' => ['label' => 'Instagram', 'icon' => 'fa-instagram'],
        'tiktok' => ['label' => 'TikTok', 'icon' => 'fa-tiktok'],
        'snapchat' => ['label' => 'Snapchat', 'icon' => 'fa-snapchat'],
        'x' => ['label' => 'X / Twitter', 'icon' => 'fa-x-twitter'],
        'linkedin' => ['label' => 'LinkedIn', 'icon' => 'fa-linkedin-in'],
        'youtube' => ['label' => 'YouTube', 'icon' => 'fa-youtube'],
        'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'fa-whatsapp'],
    ],

    'uploads' => [
        'image_max_kb' => 8192,
        'video_max_kb' => 102400,
        'pdf_max_kb' => 20480,
        'image_max_width' => 2000,
        'webp_quality' => 82,
    ],
];
