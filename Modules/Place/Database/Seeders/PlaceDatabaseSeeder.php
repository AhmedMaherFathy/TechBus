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
            'القاهرة القديمة',
            'المعادي', // Added for M9 route
            'التجمع الخامس', // Added for M9 route
            'منطقة المرح', // Added for route 18
            'المرح', // Added for route 18
            'الزيتون', // Added for route 18
            'سراي القبة', // Added for route 18
            'كوبري القبة', // Added for route 18
            'حدائق القبة', // Added for route 18
            'منطقة العباسية', // Added for route 18
            'بولاق الدكرور', // Added for route 50
            'فيصل', // Added for route 50
            'الدقي', // Added for route 50
            'الجيزة', // Added for route 50
            'القصر العيني', // Added for route 50
            'وسط البلد', // Added for route 50
            'رمسيس', // Added for route 50
            'غمرة', // Added for route 50
            'التجمع الأول', // Added for route 50
            'الرحاب', // Added for route 50
        ];

        $zoneModels = [];

        foreach ($zones as $index => $zoneName) {
            $zoneId = 'Z-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT); // Format zone ID
            $zoneModels[$zoneName] = Zone::create(['custom_id' => $zoneId, 'name' => $zoneName]);
        }

        // Define all stations
        $stations = [
            ['name' => 'بيجام', 'lat' => '30.14044', 'long' => '31.24464', 'zone_id' => $zoneModels['شبرا الخيمة']->id],
            ['name' => 'شبرا الخيمه', 'lat' => '30.12679', 'long' => '31.25990', 'zone_id' => $zoneModels['شبرا الخيمة']->id],
            ['name' => 'الشارع الجديد', 'lat' => '30.13152', 'long' => '31.28723', 'zone_id' => $zoneModels['شبرا الخيمة']->id],
            ['name' => 'مسطرد', 'lat' => '30.14464', 'long' => '31.28608', 'zone_id' => $zoneModels['المرج']->id],
            ['name' => 'المطرية', 'lat' => '30.12910', 'long' => '31.31864', 'zone_id' => $zoneModels['المطرية']->id],
            ['name' => 'الحلمية', 'lat' => '30.03746', 'long' => '31.25213', 'zone_id' => $zoneModels['عين شمس']->id],
            ['name' => 'التجنيد', 'lat' => '30.05114', 'long' => '31.31237', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'المحكمة', 'lat' => '30.10413', 'long' => '31.33037', 'zone_id' => $zoneModels['مصر الجديدة']->id],
            ['name' => 'سفير', 'lat' => '30.09742', 'long' => '31.33787', 'zone_id' => $zoneModels['القاهرة الجديدة']->id],
            ['name' => 'نادي الجلاء', 'lat' => '30.09751', 'long' => '31.34874', 'zone_id' => $zoneModels['القاهرة']->id],
            ['name' => 'السبع عمارات', 'lat' => '30.08981', 'long' => '31.34217', 'zone_id' => $zoneModels['مصر الجديدة']->id],
            ['name' => 'اول عباس', 'lat' => '30.06793', 'long' => '31.33894', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'اول مكرم', 'lat' => '30.06959', 'long' => '31.34401', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'معهد الخدمة الاجتماعية', 'lat' => '30.06818', 'long' => '31.35268', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'النادي الاهلي', 'lat' => '30.07066', 'long' => '31.35578', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'مساكن الشروق', 'lat' => '30.06901', 'long' => '31.36004', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'الخصوص', 'lat' => '30.16350', 'long' => '31.31240', 'zone_id' => $zoneModels['القليوبية']->id],
            ['name' => 'الشركات', 'lat' => '30.04847', 'long' => '31.26179', 'zone_id' => $zoneModels['القاهرة']->id],
            ['name' => 'مسطرد تحت الكوبري', 'lat' => '30.13944', 'long' => '31.29198', 'zone_id' => $zoneModels['القاهرة']->id],
            ['name' => 'الرشاح', 'lat' => '30.11989', 'long' => '31.29972', 'zone_id' => $zoneModels['القاهرة']->id],
            ['name' => 'التعاون', 'lat' => '30.04474', 'long' => '31.328822', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'ميدان المطرية', 'lat' => '30.10165', 'long' => '31.29989', 'zone_id' => $zoneModels['المطرية']->id],
            ['name' => 'ميدان الحلمية', 'lat' => '30.03612', 'long' => '31.25486', 'zone_id' => $zoneModels['الحلمية']->id],
            ['name' => 'محكمة', 'lat' => '30.04942', 'long' => '31.25303', 'zone_id' => $zoneModels['القاهرة القديمة']->id],
            ['name' => 'كوبري النزهة', 'lat' => '30.08377', 'long' => '31.34026', 'zone_id' => $zoneModels['النزهة']->id],
            ['name' => 'ميدان الساعة', 'lat' => '30.06973', 'long' => '31.33828', 'zone_id' => $zoneModels['القاهرة']->id],
            ['name' => 'سيتي سنتر', 'lat' => '30.06897', 'long' => '31.34458', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'مكرم عبيد', 'lat' => '30.06286', 'long' => '31.34451', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'مصطفي النحاس', 'lat' => '30.05529', 'long' => '31.34812', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'جامع المعصراوي', 'lat' => '30.06006', 'long' => '31.35830', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'الحي الثامن', 'lat' => '30.06021', 'long' => '31.357958', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'المنهل', 'lat' => '30.05631', 'long' => '31.35806', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'التبه', 'lat' => '30.06819', 'long' => '31.37676', 'zone_id' => $zoneModels['القاهرة']->id],
            ['name' => 'الحي العاشر', 'lat' => '30.017075', 'long' => '31.367654', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'زهراء مدينة نصر', 'lat' => '30.05684', 'long' => '31.38482', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'التجمع الاول', 'lat' => '30.03868', 'long' => '31.47155', 'zone_id' => $zoneModels['القاهرة الجديدة']->id],
            // Add new stations for M9, 18, and 50
            ['name' => 'ميدان الاتحاد', 'lat' => '31.25061', 'long' => '31.25061', 'zone_id' => $zoneModels['المعادي']->id],
            ['name' => 'محطة مترو المعادي', 'lat' => '31.25772', 'long' => '31.25772', 'zone_id' => $zoneModels['المعادي']->id],
            ['name' => 'صقر قريش', 'lat' => '31.28696', 'long' => '31.28696', 'zone_id' => $zoneModels['المعادي']->id],
            ['name' => 'محطة مترو مول تجاري', 'lat' => '31.39541', 'long' => '31.39541', 'zone_id' => $zoneModels['التجمع الخامس']->id],
            ['name' => 'محكمة القاهرة الجديدة', 'lat' => '31.42616', 'long' => '31.42616', 'zone_id' => $zoneModels['التجمع الخامس']->id],
            ['name' => 'مستشفى سكاي التخصصي', 'lat' => '31.41563', 'long' => '31.41563', 'zone_id' => $zoneModels['التجمع الخامس']->id],
            ['name' => 'الجامعة الامريكية', 'lat' => '31.42848', 'long' => '31.42848', 'zone_id' => $zoneModels['التجمع الخامس']->id],
            ['name' => 'منطقة اللونس', 'lat' => '31.52013', 'long' => '31.52013', 'zone_id' => $zoneModels['التجمع الخامس']->id],
            ['name' => 'المرح الجديد', 'lat' => '31.33620', 'long' => '31.33620', 'zone_id' => $zoneModels['منطقة المرح']->id],
            ['name' => 'محطة مترو المرح', 'lat' => '31.33836', 'long' => '31.33836', 'zone_id' => $zoneModels['المرح']->id],
            ['name' => 'عين شمس', 'lat' => '31.32933', 'long' => '31.32933', 'zone_id' => $zoneModels['عين شمس']->id],
            ['name' => 'حامية الزيتون', 'lat' => '31.31714', 'long' => '31.31714', 'zone_id' => $zoneModels['الزيتون']->id],
            ['name' => 'سراي القبة', 'lat' => '31.30783', 'long' => '31.30783', 'zone_id' => $zoneModels['سراي القبة']->id],
            ['name' => 'كوبري القبة', 'lat' => '31.29171', 'long' => '31.29171', 'zone_id' => $zoneModels['كوبري القبة']->id],
            ['name' => 'حدائق القبة', 'lat' => '31.27802', 'long' => '31.27802', 'zone_id' => $zoneModels['حدائق القبة']->id],
            ['name' => 'العباسية', 'lat' => '31.71605', 'long' => '31.71605', 'zone_id' => $zoneModels['منطقة العباسية']->id],
            ['name' => 'موقف زنين', 'lat' => '31.18364', 'long' => '31.18364', 'zone_id' => $zoneModels['بولاق الدكرور']->id],
            ['name' => 'شارع العشرين', 'lat' => '31.18568', 'long' => '31.18568', 'zone_id' => $zoneModels['فيصل']->id],
            ['name' => 'كوبري ثروت', 'lat' => '31.20110', 'long' => '31.20110', 'zone_id' => $zoneModels['الدقي']->id],
            ['name' => 'جامعة القاهرة', 'lat' => '31.20874', 'long' => '31.20874', 'zone_id' => $zoneModels['الجيزة']->id],
            ['name' => 'حديقة الحيوان', 'lat' => '31.21336', 'long' => '31.21336', 'zone_id' => $zoneModels['الجيزة']->id],
            ['name' => 'القصر العيني', 'lat' => '31.22370', 'long' => '31.22370', 'zone_id' => $zoneModels['القصر العيني']->id],
            ['name' => 'ميدان التحرير', 'lat' => '31.23578', 'long' => '31.23578', 'zone_id' => $zoneModels['وسط البلد']->id],
            ['name' => 'ميدان عبد المنعم رياض', 'lat' => '31.23439', 'long' => '31.23439', 'zone_id' => $zoneModels['وسط البلد']->id],
            ['name' => 'الاسماف', 'lat' => '31.23442', 'long' => '31.23442', 'zone_id' => $zoneModels['وسط البلد']->id],
            ['name' => 'رمسيس', 'lat' => '31.25887', 'long' => '31.25887', 'zone_id' => $zoneModels['رمسيس']->id],
            ['name' => 'غمرة', 'lat' => '31.26560', 'long' => '31.26560', 'zone_id' => $zoneModels['غمرة']->id],
            ['name' => 'وزارة المالية', 'lat' => '31.30103', 'long' => '31.30103', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'نادي السكة', 'lat' => '31.32697', 'long' => '31.32697', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'جامعة الأزهر', 'lat' => '31.32187', 'long' => '31.32187', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'رابعه العدوية', 'lat' => '31.32236', 'long' => '31.32236', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'ميدان هشام بركات', 'lat' => '31.32585', 'long' => '31.32585', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'التأمين الصحي', 'lat' => '31.32453', 'long' => '31.32453', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'مسجد نوري خطاب', 'lat' => '31.32661', 'long' => '31.32661', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'الحي السابع', 'lat' => '31.32559', 'long' => '31.32559', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'ذاكر حسين', 'lat' => '30.04483', 'long' => '30.04483', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'انبي', 'lat' => '30.04514', 'long' => '30.04514', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'الوفاء والأمل', 'lat' => '30.02991', 'long' => '30.02991', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'احمد الزمر', 'lat' => '30.04767', 'long' => '30.04767', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'جامع السلام', 'lat' => '30.04881', 'long' => '30.04881', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'سوق السيارات', 'lat' => '30.05042', 'long' => '30.05042', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'الحي العاشر', 'lat' => '30.07453', 'long' => '30.07453', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'زهراء مدينة نصر', 'lat' => '30.05654', 'long' => '30.05654', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'شارع الميثاق', 'lat' => '30.05136', 'long' => '30.05136', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'السنترال', 'lat' => '30.05173', 'long' => '30.05173', 'zone_id' => $zoneModels['مدينة نصر']->id],
            ['name' => 'اكاديمية الشرطة', 'lat' => '30.04688', 'long' => '30.04688', 'zone_id' => $zoneModels['التجمع الأول']->id],
            ['name' => 'هابير لولو', 'lat' => '30.02864', 'long' => '30.02864', 'zone_id' => $zoneModels['التجمع الأول']->id],
            ['name' => 'صينية الشباب', 'lat' => '30.05479', 'long' => '30.05479', 'zone_id' => $zoneModels['التجمع الأول']->id],
            ['name' => 'التجمع الأول', 'lat' => '30.03794', 'long' => '30.03794', 'zone_id' => $zoneModels['التجمع الأول']->id],
            ['name' => 'بوابة (6)', 'lat' => '29.97558', 'long' => '29.97558', 'zone_id' => $zoneModels['الرحاب']->id],
        ];

        $stationModels = [];
        foreach ($stations as $index => $station) {
            $customId = 'S-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $station['custom_id'] = $customId;
            $stationModels[$station['name']] = Station::create($station);
        }

        // Data for Route 26
        $route26 = Route::create(['custom_id' => 'R-001', 'name' => 'Route 26', 'number' => '26']);
        $route26Stations = [
            'بيجام',
            'شبرا الخيمه',
            'الشارع الجديد',
            'مسطرد',
            'المطرية',
            'الحلمية',
            'التجنيد',
            'المحكمة',
            'سفير',
            'نادي الجلاء',
            'السبع عمارات',
            'اول عباس',
            'اول مكرم',
            'معهد الخدمة الاجتماعية',
            'النادي الاهلي',
            'مساكن الشروق'
        ];

        foreach ($route26Stations as $index => $stationName) {
            $route26->stations()->attach($stationModels[$stationName]->id, ['order' => $index + 1]);
        }

        // Data for Route 308
        $route308 = Route::create(['custom_id' => 'R-002', 'name' => 'Route 308', 'number' => '308']);

        $route308Stations = [
            'الخصوص',
            'الشركات',
            'مسطرد تحت الكوبري',
            'الرشاح',
            'التعاون',
            'ميدان المطرية',
            'ميدان الحلمية',
            'التجنيد',
            'محكمة',
            'سفير',
            'نادي الجلاء',
            'السبع عمارات',
            'كوبري النزهة',
            'ميدان الساعة',
            'اول عباس',
            'سيتي سنتر',
            'مكرم عبيد',
            'مصطفي النحاس',
            'جامع المعصراوي',
            'الحي الثامن',
            'المنهل',
            'التبه',
            'الحي العاشر',
            'زهراء مدينة نصر',
            'التجمع الاول'
        ];

        foreach ($route308Stations as $index => $stationName) {
            if (isset($stationModels[$stationName])) {
                $route308->stations()->attach($stationModels[$stationName]->id, ['order' => $index + 1]);
            }
        }

        // Data for Route M9
        $routeM9 = Route::create(['custom_id' => 'R-003', 'name' => 'Route M9', 'number' => 'M9']);
        $routeM9Stations = [
            'ميدان الاتحاد',
            'محطة مترو المعادي',
            'صقر قريش',
            'محطة مترو مول تجاري',
            'محكمة القاهرة الجديدة',
            'مستشفى سكاي التخصصي',
            'الجامعة الامريكية',
            'منطقة اللونس'
        ];

        foreach ($routeM9Stations as $index => $stationName) {
            if (isset($stationModels[$stationName])) {
                $routeM9->stations()->attach($stationModels[$stationName]->id, ['order' => $index + 1]);
            }
        }

        // Data for Route 18
        $route18 = Route::create(['custom_id' => 'R-004', 'name' => 'Route 18', 'number' => '18']);
        $route18Stations = [
            'المرح الجديد',
            'محطة مترو المرح',
            'عين شمس',
            'حامية الزيتون',
            'المطرية',
            'سراي القبة',
            'كوبري القبة',
            'حدائق القبة',
            'العباسية'
        ];

        foreach ($route18Stations as $index => $stationName) {
            if (isset($stationModels[$stationName])) {
                $route18->stations()->attach($stationModels[$stationName]->id, ['order' => $index + 1]);
            }
        }

        // Data for Route 50
        $route50 = Route::create(['custom_id' => 'R-005', 'name' => 'Route 50', 'number' => '50']);
        $route50Stations = [
            'موقف زنين',
            'شارع العشرين',
            'كوبري ثروت',
            'جامعة القاهرة',
            'حديقة الحيوان',
            'القصر العيني',
            'ميدان التحرير',
            'ميدان عبد المنعم رياض',
            'الاسماف',
            'رمسيس',
            'غمرة',
            'العباسية',
            'وزارة المالية',
            'نادي السكة',
            'جامعة الأزهر',
            'رابعه العدوية',
            'ميدان هشام بركات',
            'التأمين الصحي',
            'مسجد نوري خطاب',
            'الحي السابع',
            'ذاكر حسين',
            'انبي',
            'الوفاء والأمل',
            'احمد الزمر',
            'جامع السلام',
            'سوق السيارات',
            'الحي العاشر',
            'زهراء مدينة نصر',
            'شارع الميثاق',
            'السنترال',
            'اكاديمية الشرطة',
            'هابير لولو',
            'صينية الشباب',
            'التجمع الأول',
            'بوابة (6)'
        ];

        foreach ($route50Stations as $index => $stationName) {
            if (isset($stationModels[$stationName])) {
                $route50->stations()->attach($stationModels[$stationName]->id, ['order' => $index + 1]);
            }
        }
    }
}
