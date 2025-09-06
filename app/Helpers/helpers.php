<?php

use Illuminate\Support\Carbon;

if (!function_exists('formatPrice')) {
    function formatPrice($number)
    {
        // Remove commas and cast to float
        $number = str_replace(',', '', $number);

        if (!is_numeric($number)) {
            return 'N/A'; // or return 0, or '' depending on your UX
        }

        $number = (float) $number;

        if ($number >= 1000000) {
            return number_format($number / 1000000, ($number % 1000000 === 0) ? 0 : 1) . 'M';
        } elseif ($number >= 1000) {
            return number_format($number / 1000, ($number % 1000 === 0) ? 0 : 1) . 'K';
        }

        return number_format($number);
    }
}



if (!function_exists('format_thai_phone')) {
    function format_thai_phone($phone)
    {
        // Remove non-digit characters
        $cleanPhone = preg_replace('/\D/', '', $phone);

        if (strlen($cleanPhone) === 9) {
            // Format 9-digit number: 812-345-678
            return preg_replace("/^(\d{3})(\d{3})(\d{3})$/", "$1-$2-$3", $cleanPhone);
        } elseif (strlen($cleanPhone) === 10) {
            // Format 10-digit number: 081-234-5678
            return preg_replace("/^(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $cleanPhone);
        }

        // Return original if format doesn't match
        return $phone;
    }
}

if (!function_exists('addCommas')) {
    function addCommas($number)
    {
        // Remove existing commas
        $cleaned = str_replace(',', '', $number);

        // Ensure it's a numeric value
        if (!is_numeric($cleaned)) {
            return 'Invalid number';
        }

        // Cast to float or int and format with commas
        return number_format($cleaned);
    }
}



if (!function_exists('thaiDate')) {
    function thaiDate($date)
    {
        $thaiMonths = [
            1 => 'ม.ค.',
            2 => 'ก.พ.',
            3 => 'มี.ค.',
            4 => 'เม.ย.',
            5 => 'พ.ค.',
            6 => 'มิ.ย.',
            7 => 'ก.ค.',
            8 => 'ส.ค.',
            9 => 'ก.ย.',
            10 => 'ต.ค.',
            11 => 'พ.ย.',
            12 => 'ธ.ค.'
        ];

        $carbonDate = Carbon::parse($date);
        $day = $carbonDate->format('d');
        $month = $thaiMonths[(int)$carbonDate->format('m')];
        $year = $carbonDate->year + 543;

        return "{$day} {$month} {$year}";
    }
}

if (!function_exists('thaiMonthYear')) {
    function thaiMonthYear($date)
    {
        $thaiMonths = [
            1 => 'ม.ค.',
            2 => 'ก.พ.',
            3 => 'มี.ค.',
            4 => 'เม.ย.',
            5 => 'พ.ค.',
            6 => 'มิ.ย.',
            7 => 'ก.ค.',
            8 => 'ส.ค.',
            9 => 'ก.ย.',
            10 => 'ต.ค.',
            11 => 'พ.ย.',
            12 => 'ธ.ค.'
        ];

        $carbonDate = Carbon::parse($date);
        $month = $thaiMonths[(int)$carbonDate->format('m')];
        $year = $carbonDate->year + 543;

        return "{$month} {$year}";
    }
}


if (!function_exists('thaiDateNoYearWithTime')) {
    function thaiDateNoYearWithTime($date)
    {
        $thaiMonths = [
            1 => 'ม.ค.',
            2 => 'ก.พ.',
            3 => 'มี.ค.',
            4 => 'เม.ย.',
            5 => 'พ.ค.',
            6 => 'มิ.ย.',
            7 => 'ก.ค.',
            8 => 'ส.ค.',
            9 => 'ก.ย.',
            10 => 'ต.ค.',
            11 => 'พ.ย.',
            12 => 'ธ.ค.'
        ];

        $carbonDate = \Carbon\Carbon::parse($date);
        $day = $carbonDate->format('d');
        $month = $thaiMonths[(int) $carbonDate->format('m')];
        $time = $carbonDate->format('H:i');

        return "{$day} {$month} เวลา {$time}";
    }
}
