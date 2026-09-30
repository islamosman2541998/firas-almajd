<?php

use App\Models\Setting;
use App\Support\SiteCache;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /** Renames the stored contact CR label to "رقم السجل التجاري" on existing installs. */
    public function up(): void
    {
        Setting::where('key', 'like', 'section.%')->get()->each(function (Setting $setting) {
            $value = $setting->value;
            if (($value['cr_label']['ar'] ?? null) === 'السجل التجاري رقم') {
                $value['cr_label']['ar'] = 'رقم السجل التجاري';
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
