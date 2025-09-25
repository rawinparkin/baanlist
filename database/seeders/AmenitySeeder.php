<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('Amenities')->insert([
            [
                'amenity_name' => 'เครื่องปรับอากาศ',
                'amenity_icon' => 'im im-icon-Snowflake',
                'created_at' => now(),
            ],
            [
                'amenity_name' => 'อินเทอร์เน็ต',
                'amenity_icon' => 'im im-icon-Internet',
                'created_at' => now(),
            ],
            [
                'amenity_name' => 'ที่จอดรถ',
                'amenity_icon' => 'im im-icon-Car-2',
                'created_at' => now(),
            ],
            [
                'amenity_name' => 'ลิฟต์',
                'amenity_icon' => 'im im-icon-Elevator',
                'created_at' => now(),
            ],
            [
                'amenity_name' => 'สระว่ายน้ำ',
                'amenity_icon' => 'im im-icon-Swimming',
                'created_at' => now(),
            ],
            [
                'amenity_name' => 'ฟิตเนส',
                'amenity_icon' => 'im im-icon-Dumbbell',
                'created_at' => now(),
            ],
            [
                'amenity_name' => 'เครื่องทำน้ำอุ่น',
                'amenity_icon' => 'im im-icon-Drop',
                'created_at' => now(),
            ],
            [
                'amenity_name' => 'กล้องวงจรปิด',
                'amenity_icon' => 'im im-icon-Camera-2',
                'created_at' => now(),
            ],
            [
                'amenity_name' => 'ระบบรักษาความปลอดภัย',
                'amenity_icon' => 'im im-icon-Shield',
                'created_at' => now(),
            ],
            [
                'amenity_name' => 'สวนหย่อม',
                'amenity_icon' => 'im im-icon-Leafs',
                'created_at' => now(),
            ],
            [
                'amenity_name' => 'ระเบียง',
                'amenity_icon' => 'im im-icon-Sun',
                'created_at' => now(),
            ],
            [
                'amenity_name' => 'เฟอร์นิเจอร์ครบ',
                'amenity_icon' => 'im im-icon-Furniture',
                'created_at' => now(),
            ],
            [
                'amenity_name' => 'อนุญาตให้เลี้ยงสัตว์',
                'amenity_icon' => 'im im-icon-Dog',
                'created_at' => now(),
            ],
        ]);
    }
}