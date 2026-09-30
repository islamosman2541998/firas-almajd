<?php

use App\Models\Setting;
use App\Support\SiteCache;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /** Shows the contact P.O. Box label in English ("P.O.Box") on the Arabic site too. */
    public function up(): void
    {
        Setting::where('key', 'like', 'section.%')->get()->each(function (Setting $setting) {
            $value = $setting->value;
            if (($value['po_box_label']['ar'] ?? null) === 'صندوق البريد') {
                $value['po_box_label']['ar'] = 'P.O.Box';
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
