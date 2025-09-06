<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackagePlan;

class PackageController extends Controller
{
    public function AdminAllPackage()
    {
        $plan = PackagePlan::all();
        return view('admin.package.all_package', compact('plan'));
    } // End method

    public function AdminEditPackage($id)
    {
        $package = PackagePlan::findOrFail($id);
        return view('admin.package.edit_package', compact('package'));
    } // End method

    public function AdminPackageUpdate(Request $request)
    {

        $pid = $request->id;
        PackagePlan::findOrFail($pid)->update([

            'package_name' => $request->package_name,
            'package_cost' => $request->package_cost,
            'cost_desc'    => $request->cost_desc,
            'price'        => $request->price,
            'package_credits' => $request->package_credits,
            'billing_type' => $request->billing_type,
            'validity_days' => $request->validity_days,
            'is_featured'  => $request->is_featured,
            'feature_desc' => $request->feature_desc,
            'description'  => $request->description,
        ]);
        return redirect()->route('admin.all.package')->with('success', 'แก้ไขแพ็คเกจเรียบร้อยแล้ว!');
    } // End Method 
}
