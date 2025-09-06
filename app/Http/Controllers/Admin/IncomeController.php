<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPlan;
use Carbon\Carbon;

class IncomeController extends Controller
{
    public function AdminAllIncome()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonthDate = Carbon::now()->subMonth();
        $lastMonth = $lastMonthDate->month;
        $lastMonthYear = $lastMonthDate->year;

        $income1 = UserPlan::whereMonth('activated_at', $currentMonth)
            ->whereYear('activated_at', $currentYear)
            ->get();

        $income2 = UserPlan::whereMonth('activated_at', $lastMonth)
            ->whereYear('activated_at', $lastMonthYear)
            ->get();

        $income3 = UserPlan::whereYear('activated_at', $currentYear)
            ->get();

        $income4 = UserPlan::all();

        return view('admin.income.all_income', compact('income1', 'income2', 'income3', 'income4'));
    }

    public function AdminReceipt($id)
    {

        // Only fetch the record if it belongs to the logged-in user
        $receipt = UserPlan::where('id', $id)
            ->first();

        // If not found or not authorized, abort
        if (!$receipt) {
            abort(403, 'Unauthorized access to receipt.');
        }
        // Format the date in Thai Buddhist calendar
        if ($receipt->activated_at) {
            $date = Carbon::parse($receipt->activated_at);
            $receipt->formatted_activated_at = $date->translatedFormat('d/m/') . ($date->year + 543);
        } else {
            $receipt->formatted_activated_at = '-';
        }
        return view('frontend.dashboard.package.receipt', compact('receipt'));
    }
}
