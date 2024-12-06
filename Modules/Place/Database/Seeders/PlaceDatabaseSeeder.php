<?php

namespace Modules\Place\Database\Seeders;

use Modules\Place\Models\Zone;
use Illuminate\Database\Seeder;
use Modules\Place\Models\Route;
use Modules\Place\Models\Station;

class PlaceDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = [
            'شبرا الخيمة',
            'المرج',
            'المطرية',
            'الحلمية',
            'عين شمس',
            'مصر الجديدة',
            'مدينة نصر',
            'مدينة الشروق',
            'القليوبية',
            'القاهرة',
            'النزهة',
            'مدينة نصر الجديدة',
            'القاهرة الجديدة',
            "القاهرة القديمة",
        ];

        $zoneModels = [];
        foreach ($zones as $zoneName) {
            $zoneModels[$zoneName] = Zone::create(['name' => $zoneName]);
        }

        // Define all stations
        $stations = [
            ['name' => 'بيجام', 'lat' => '30.14044', 'long' => '31.24464', 'zone_id' => $zoneModels['شبرا الخيمة']->id],
            ['name' => 'شبرا الخيمه', 'lat' => '30.12679', 'long' => '31.25990', 'zone_id' => $zoneModels['شبرا الخيمة']->id],
            ['name' => 'الشارع الجديد', 'lat' => '30.13152', 'long' => '31.28723', 'zone_id' => $zoneModels['شبرا الخيمة']->id],
            ['name' => 'مسطرد', 'lat' => '30.14464', 'long' => '31.28608', 'zone_id' => $zoneModels['المرج']->id],
            ['name' => 'المطرية', 'lat' => '30.12910', 'long' => '31.31864', 'zone_id' => $zoneModels['المطرية']->id],
            ['name' => 'الحلمية', 'lat' => '30.03746', 'long' => '31.25213', 'zone_id' => $zoneModels['عين شمس']->id],
            ['name' => 'التجنيد', 'lat' => '30.11027', 'long' => '31.32496', 'zone_id' => $zoneModels['الحلمية']->id],
            ['name' => 'المحكمة', 'lat' => '30.10413', 'long' => '31.33037', 'zone_id' => $zoneModels['مصر الجديدة']->id],
            ['name' => 'سفير', 'lat' => '30.09910', 'long' => '31.33976', 'zone_id' => $zoneModels['مصر الجديدة']->id],
            ['name' => 'نادي الجلاء', 'lat' => '30.09755', 'long' => '31.34878', 'zone_id' => $zoneModels['مصر الجديدة']->id],
            ['name' => 'السبع عمارات', 'lat' => '30.08973', 'long' => '31.34218', 'zone_id' => $zoneModels['مصر الجديدة']->id],
            ['name' => 'اول عباس', 'lat' => '30.06728', 'long' => '31.33914', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'اول مكرم', 'lat' => '30.06959', 'long' => '31.34401', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'معهد الخدمة الاجتماعية', 'lat' => '30.06818', 'long' => '31.35268', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'النادي الاهلي', 'lat' => '30.07066', 'long' => '31.35578', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'مساكن الشروق', 'lat' => '30.06901', 'long' => '31.36004', 'zone_id' => $zoneModels['مدينة الشروق']->id],
            ['name' => 'الخصوص', 'lat' => '30.16350', 'long' => '31.31240', 'zone_id' => $zoneModels['القليوبية']->id],
            ['name' => 'الشركات', 'lat' => '30.04847', 'long' => '31.26179', 'zone_id' => $zoneModels['القاهرة']->id],
            ['name' => 'مسطرد تحت الكوبري', 'lat' => '30.13944', 'long' => '31.29198', 'zone_id' => $zoneModels['القاهرة']->id],
            ['name' => 'الرشاح', 'lat' => '30.11989', 'long' => '31.29972', 'zone_id' => $zoneModels['القاهرة']->id],
            ['name' => 'التعاون', 'lat' => '30.04474', 'long' => '31.328822', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'ميدان المطرية', 'lat' => '30.10165', 'long' => '31.29989', 'zone_id' => $zoneModels['المطرية']->id],
            ['name' => 'ميدان الحلمية', 'lat' => '30.03612', 'long' => '31.25486', 'zone_id' => $zoneModels['الحلمية']->id],
            ['name' => 'محكمة', 'lat' => '30.04942', 'long' => '31.25303', 'zone_id' => $zoneModels['القاهرة']->id],
            ['name' => 'كوبري النزهة', 'lat' => '30.08377', 'long' => '31.34026', 'zone_id' => $zoneModels['النزهة']->id],
            ['name' => 'ميدان الساعة', 'lat' => '30.06973', 'long' => '31.33828', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'زهراء مدينة نصر', 'lat' => '30.05684', 'long' => '31.38482', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'التجمع الاول', 'lat' => '30.03868', 'long' => '31.47155', 'zone_id' => $zoneModels['القاهرة الجديدة']->id],
        ];

        $stationModels = [];
        foreach ($stations as $station) {
            $stationModels[$station['name']] = Station::create($station);
        }

        // Data for Route 26
        $route26 = Route::create(['name' => 'Route 26', 'number' => '26']);
        $route26Stations = [
            'بيجام', 'شبرا الخيمه', 'الشارع الجديد', 'مسطرد', 'المطرية', 'الحلمية', 'التجنيد',
            'المحكمة', 'سفير', 'نادي الجلاء', 'السبع عمارات', 'اول عباس', 'اول مكرم',
            'معهد الخدمة الاجتماعية', 'النادي الاهلي', 'مساكن الشروق'
        ];

        foreach ($route26Stations as $index => $stationName) {
            $route26->stations()->attach($stationModels[$stationName]->id, ['order' => $index + 1]);
        }

        // Data for Route 308
        $route308 = Route::create(['name' => 'Route 308', 'number' => '308']);
        $route308Stations = [
            'الخصوص', 'الشركات', 'مسطرد تحت الكوبري', 'الرشاح', 'التعاون', 'ميدان المطرية',
            'ميدان الحلمية', 'التجنيد', 'محكمة', 'كوبري النزهة', 'ميدان الساعة', 'زهراء مدينة نصر',
            'التجمع الاول'
        ];

        foreach ($route308Stations as $index => $stationName) {
            $route308->stations()->attach($stationModels[$stationName]->id, ['order' => $index + 1]);
        }
    }
}
