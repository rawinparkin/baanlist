<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubDistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = Storage::disk('local')->get('data/subdistricts.csv');
        $lines = explode(PHP_EOL, $file);
        $header = str_getcsv(array_shift($lines));

        foreach ($lines as $line) {
            if (trim($line) === '') continue;
            $row = array_combine($header, str_getcsv($line));

            DB::table('sub_district_test')->insert([
                'zip_code' => $row['zip_code'],
                'name_th' => $row['name_th'],
                'name_en' => $row['name_en'] ?? null,
                'district_id' => $row['district_id'],
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
            ]);
        }
    }
}