<?php

/*
| Dashboard sidebar. "active" is a route-name pattern; "badge" a key resolved
| in admin/partials/sidebar (unread counts).
*/

return [
    'nav' => [
        'main' => [
            ['route' => 'admin.dashboard', 'icon' => 'bi-grid-1x2', 'label' => 'dashboard'],
        ],
        'content' => [
            ['route' => 'admin.sections', 'icon' => 'bi-layout-text-window', 'label' => 'sections', 'active' => 'admin.sections'],
            ['route' => 'admin.sliders.index', 'icon' => 'bi-images', 'label' => 'sliders', 'active' => 'admin.sliders.*'],
            ['route' => 'admin.services.index', 'icon' => 'bi-bricks', 'label' => 'services', 'active' => 'admin.services.*'],
            ['route' => 'admin.projects.index', 'icon' => 'bi-buildings', 'label' => 'projects', 'active' => 'admin.projects.*'],
            ['route' => 'admin.gallery.index', 'icon' => 'bi-grid-3x3-gap', 'label' => 'gallery', 'active' => 'admin.gallery.*'],
            ['route' => 'admin.certificates.index', 'icon' => 'bi-patch-check', 'label' => 'certificates', 'active' => 'admin.certificates.*'],
            ['route' => 'admin.partners.index', 'icon' => 'bi-people', 'label' => 'partners', 'active' => 'admin.partners.*'],
            ['route' => 'admin.faqs.index', 'icon' => 'bi-question-square', 'label' => 'faqs', 'active' => 'admin.faqs.*'],
            ['route' => 'admin.pages.index', 'icon' => 'bi-file-earmark-richtext', 'label' => 'pages', 'active' => 'admin.pages.*'],
            ['route' => 'admin.menu', 'icon' => 'bi-list-nested', 'label' => 'menu'],
        ],
        'inbox' => [
            ['route' => 'admin.messages.index', 'icon' => 'bi-envelope', 'label' => 'messages', 'badge' => 'messages'],
            ['route' => 'admin.jobs.index', 'icon' => 'bi-briefcase', 'label' => 'jobs'],
            ['route' => 'admin.applications.index', 'icon' => 'bi-person-lines-fill', 'label' => 'applications', 'badge' => 'applications'],
        ],
        'settings' => [
            ['route' => 'admin.settings.general', 'icon' => 'bi-sliders', 'label' => 'settings_general'],
            ['route' => 'admin.settings.seo', 'icon' => 'bi-search', 'label' => 'settings_seo'],
            ['route' => 'admin.settings.pixels', 'icon' => 'bi-activity', 'label' => 'settings_pixels'],
            ['route' => 'admin.translations', 'icon' => 'bi-translate', 'label' => 'translations'],
            ['route' => 'admin.settings.login', 'icon' => 'bi-box-arrow-in-right', 'label' => 'settings_login'],
            ['route' => 'admin.settings.dashboard', 'icon' => 'bi-window-sidebar', 'label' => 'settings_dashboard'],
            ['route' => 'admin.settings.theme', 'icon' => 'bi-palette', 'label' => 'settings_theme'],
        ],
        'system' => [
            ['route' => 'admin.users.index', 'icon' => 'bi-shield-lock', 'label' => 'users'],
            ['route' => 'admin.notifications', 'icon' => 'bi-bell', 'label' => 'notifications'],
        ],
    ],
];
