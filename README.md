# فراس المجد — Firas Al Majd

موقع شركة فراس المجد العمرانية (عربي / إنجليزي) مع لوحة تحكم كاملة.
Laravel 13 · Livewire 4 · Bootstrap 5.3 · MySQL — بدون خطوة build (كل الأصول في `public/`).

## التشغيل على Laragon

```bash
composer install
cp .env.example .env        # ثم ضبط DB_* و APP_URL
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

- الموقع: `http://firass-almajd.test` ← يحوّل إلى `/ar` أو `/en`
- لوحة التحكم: `/admin`
- حساب البداية: `admin@firasalmajd.com` / `Admin@12345` — **غيّر كلمة المرور من «حسابي» فورًا**.
- تصدير Excel يحتاج إضافة `zip` مفعّلة في php.ini (تم تفعيلها في PHP 8.3 الخاص بـ Laragon).

### للإنتاج

```bash
APP_ENV=production APP_DEBUG=false
php artisan optimize          # config + routes + views cache
```
وفعّل OPcache في php.ini. ملف `public/.htaccess` يضبط الضغط والكاش الطويل للصور والـ CSS/JS.

## تنظيم المشروع

```
app/
  Http/Controllers/Site/      صفحات الموقع (ثابتة، خدمات، صفحات مخصصة، sitemap/robots)
  Http/Controllers/Admin/     تسجيل الدخول وتغيير لغة اللوحة
  Http/Middleware/            لغة الموقع (/ar|/en)، لغة اللوحة، إيقاف الحسابات
  Livewire/Site/              نموذج التواصل ونموذج التوظيف (الأجزاء الحية فقط في الموقع)
  Livewire/Admin/             كل شاشات اللوحة (Index / Form لكل وحدة)
  Livewire/Admin/Concerns/    سلوك مشترك: جداول (بحث، فلاتر، ترتيب، سحب، Excel)، نماذج جانبية، SEO، توستر
  Models/ + Models/Concerns/  الموديلات وترجمة الحقول (spatie/laravel-translatable) والترتيب والكاش
  Services/                   الإعدادات، الأقسام، SEO، رفع الصور (تحويل WebP)، بيانات الموقع المخزنة مؤقتًا
  Support/                    كاش الموقع، الروابط، القائمة، نصوص الواجهة من قاعدة البيانات، الإشعارات
config/
  sections.php                تعريف كل أقسام الصفحات وحقولها ونصوصها الافتراضية (من التصميم)
  settings.php                القيم الافتراضية للإعدادات (عامة، SEO، بكسلات، الدخول، اللوحة، الألوان)
  site.php / admin.php        اللغات والروابط الأساسية ومنصات التواصل / قائمة اللوحة الجانبية
resources/views/
  layouts/                    site (الموقع) و admin (اللوحة)
  site/partials/              الهيدر، الفوتر، SEO، البكسلات
  site/sections/              كل قسم في ملف مستقل (hero, about, disciplines, partners, contact, …)
  site/pages/                 قوالب الصفحات
  components/admin/           مكونات النماذج (حقول عربي/إنجليزي، رفع، ألوان، روابط، درج جانبي، …)
  livewire/                   واجهات مكونات Livewire
public/assets/site/           ملفات التصميم كما هي (styles.css, pages.css) + site.css للإضافات + app.js
public/assets/admin/          تصميم اللوحة وسكربتها
public/vendor/                المكتبات محليًا (Bootstrap, Swiper, SweetAlert2, Notyf, Bootstrap Icons, Font Awesome)
lang/{ar,en}/                 site.php (نصوص الواجهة، قابلة للتعديل من اللوحة) و admin.php
```

## كيف يعمل المحتوى الديناميكي

- **أقسام الصفحات**: كل صفحة ثابتة مكوّنة من أقسام معرّفة في `config/sections.php`. من «أقسام الصفحات» في اللوحة تتحكم في ظهور كل قسم وترتيبه (سحب) وكل نصوصه وصوره وروابطه، مع زر استعادة الافتراضي، وSEO لكل صفحة.
- **القائمة**: روابط رئيسية وروابط فرعية (قائمة منسدلة). زر «اختيار عناصر القائمة المنسدلة» يضيف خدمات أو صفحات تحت أي رابط.
- **الصفحات المخصصة**: عنوان، وصف، صورة غلاف، ومعرض (صور، MP4، روابط YouTube/Vimeo، ملفات PDF) بترتيب بالسحب. الرابط: `/{lang}/p/{slug}`.
- **السلايدر**: Swiper بتقليب تلقائي وسحب وأسهم؛ كل شريحة صورة أو فيديو، عنوان ووصف، وزر بنص ورابط وألوان مستقلة.
- **SEO**: عنوان/وصف/كلمات/صورة مشاركة/canonical/noindex لكل صفحة وخدمة وصفحة مخصصة، hreflang، Open Graph وTwitter، JSON-LD (Organization, WebSite, BreadcrumbList, Service)، `sitemap.xml` بلغتين و`robots.txt` ديناميكي.
- **البكسلات**: Meta، GA4، GTM، Google Ads، TikTok، Snapchat، X، LinkedIn، Pinterest، Clarity — يكفي إدخال المعرف. عند إرسال أي نموذج يُرسل حدث Lead لكل المنصات المفعلة.
- **الكاش والسرعة**: كل ما يقرأه الموقع من قاعدة البيانات مخزن مؤقتًا، وأي تعديل من اللوحة يحدّث الكاش تلقائيًا (`App\Support\SiteCache`). الصور المرفوعة تُصغّر وتحوّل إلى WebP. Livewire لا يُحمّل في الموقع إلا في صفحتي التواصل والوظائف.
