<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(['key' => 'qris_merchant_name'], [
            'value' => 'WiFiPay Merchant',
        ]);

        Setting::updateOrCreate(['key' => 'qris_image'], [
            'value' => null, // Upload manual via storage/app/public/qris.png nanti
        ]);
    }
}

