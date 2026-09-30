<?php

use App\Models\Setting;
use App\Support\SiteCache;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /** The contact form email is now required: drop "(optional)" from a stored label. */
    public function up(): void
    {
        $old = ['ar' => 'البريد الإلكتروني (اختياري)', 'en' => 'Email address (optional)'];
        $new = ['ar' => 'البريد الإلكتروني', 'en' => 'Email address'];

        Setting::where('key', 'like', 'section.%')->get()->each(function (Setting $setting) use ($old, $new) {
            $value = $setting->value;
            $changed = false;
            foreach ($old as $locale => $text) {
                if (($value['form_email_label'][$locale] ?? null) === $text) {
                    $value['form_email_label'][$locale] = $new[$locale];
                    $changed = true;
                }
            }
            if ($changed) {
                $setting->update(['value' => $value]);
            }
        });

        SiteCache::flush();
    }

    public function down(): void
    {
        //
    }
};
