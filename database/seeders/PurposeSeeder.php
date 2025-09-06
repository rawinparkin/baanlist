<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Purposes')->insert([

            //Admin
            [
                'purpose_name' => 'ขาย',
                'purpose_icon' => 'im im-icon-Money-2',
                'created_at' => now(),
            ],

            //Agent
            [
                'purpose_name' => 'ให้เช่า',
                'purpose_icon' => 'im im-icon-Calendar-4',
                'created_at' => now(),

            ],

            //User
            [
                'purpose_name' => 'ขายและให้เช่า',
                'purpose_icon' => 'im im-icon-Affiliate',
                'created_at' => now(),
            ],

        ]);
    }
}