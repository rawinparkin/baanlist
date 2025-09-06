<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Property_Types')->insert([

            // Home
            [

                'type_name' => 'บ้าน',
                'type_icon' => 'im im-icon-Home-2',
                'slug' => 'บ้าน',

            ],
            // Condo
            [

                'type_name' => 'คอนโด',
                'type_icon' => 'im im-icon-Building',
                'slug' => 'คอนโด',

            ],
            // Land
            [

                'type_name' => 'ที่ดิน',
                'type_icon' => 'im im-icon-Landscape',
                'slug' => 'ที่ดิน',

            ],
            // Building
            [

                'type_name' => 'อาคารพานิชย์',
                'type_icon' => 'im im-icon-Shop-2',
                'slug' => 'อาคารพานิชย์',

            ]
        ]);
    }
}