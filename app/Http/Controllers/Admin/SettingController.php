<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\SmtpSetting;
use App\Models\ContactBox;
use App\Models\Province;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\Seo;

class SettingController extends Controller
{
    public function SiteSetting()
    {
        $setting = SiteSetting::find(1);
        return view('admin.setting.site_update', compact('setting'));
    } // End Method

    public function updateSiteSetting(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,ico,webp|max:1024',
            'email' => 'nullable|email',
        ]);

        $setting = SiteSetting::find($request->id);

        if (!$setting) {
            return redirect()->back()->with('error', 'ไม่พบการตั้งค่าที่ต้องการแก้ไข');
        }

        // Upload logo
        if ($request->hasFile('logo')) {
            if ($setting->logo && file_exists(public_path('upload/logo/' . $setting->logo))) {
                unlink(public_path('upload/logo/' . $setting->logo));
            }
            $logoFile = $request->file('logo');
            $logoName = time() . '_' . $logoFile->getClientOriginalName();
            $logoFile->move(public_path('upload/logo'), $logoName);
            $setting->logo = $logoName;
        }

        // Upload favicon
        if ($request->hasFile('favicon')) {
            if ($setting->favicon && file_exists(public_path('upload/favicon/' . $setting->favicon))) {
                unlink(public_path('upload/favicon/' . $setting->favicon));
            }
            $faviconFile = $request->file('favicon');
            $faviconName = time() . '_' . $faviconFile->getClientOriginalName();
            $faviconFile->move(public_path('upload/favicon'), $faviconName);
            $setting->favicon = $faviconName;
        }

        // Update text fields
        $setting->support_phone = $request->support_phone;
        $setting->company_address = $request->company_address;
        $setting->company_lat = $request->company_lat;
        $setting->company_lon = $request->company_lon;
        $setting->email = $request->email;
        $setting->facebook = $request->facebook;

        $setting->instagram = $request->instagram;
        $setting->privacy_policy = $request->privacy_policy;
        $setting->about_footer = $request->about_footer;
        $setting->terms_of_service = $request->terms_of_service;
        $setting->copyright = $request->copyright;

        $setting->save();

        return redirect()->back()->with('success', 'การตั้งค่าถูกอัปเดตเรียบร้อยแล้ว');
    } // End Method


    public function SmtpSetting()
    {
        $setting = SmtpSetting::find(1);
        return view('admin.setting.smtp_update', compact('setting'));
    } // End Method


    public function UpdateSmtpSetting(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'mailer' => 'nullable|string|max:255',
            'host' => 'nullable|string|max:255',
            'port' => 'nullable|string|max:10',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'encryption' => 'nullable|string|max:10',
            'from_address' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ]);

        $smtp = SmtpSetting::findOrFail($request->id);

        $smtp->update([
            'name' => $request->name,
            'mailer' => $request->mailer,
            'host' => $request->host,
            'port' => $request->port,
            'username' => $request->username,
            'password' => $request->password,
            'encryption' => $request->encryption,
            'from_address' => $request->from_address,
            'from_name' => $request->from_name,
            'active' => $request->active,
        ]);

        return redirect()->back()->with('success', 'SMTP settings updated successfully.');
    } // End Method

    public function AdminAllMessage()
    {
        $message = ContactBox::all();
        return view('admin.message.all_message', compact('message'));
    }

    public function DeleteMessageBox($id)
    {
        ContactBox::findOrFail($id)->delete();
        return redirect()->route('admin.all.message')->with('success', 'ลบข้อตวามเรียบร้อยแล้ว!');
    } // End Method 

    public function ShowMessageBox($id)
    {
        $data = ContactBox::findOrFail($id);
        return view('admin.message.show_message', compact('data'));
    } // End Method 

    public function AdminAllProvince()
    {
        $province = Province::all();
        return view('admin.location.all_province', compact('province'));
    } // End method



    public function AdminEditProvince($id)
    {
        $province = Province::findOrFail($id);
        $district = District::where('province_id', $id)->get();
        return view('admin.location.edit_province', compact('province', 'district'));
    } // End method



    public function AdminProvinceUpdate(Request $request)
    {
        $pid = $request->id;
        Province::findOrFail($pid)->update([
            'name_th' => $request->name_th,
            'name_en' => $request->name_en,
        ]);
        return redirect()->route('admin.all.province')->with('success', 'แก้ไขจังหวัดเรียบร้อยแล้ว!');
    } // End Method 

    public function AdminDistrictUpdate(Request $request)
    {
        $id = $request->district_id;
        District::findOrFail($id)->update([
            'name_th' => $request->name_th,
            'name_en' => $request->name_en,
            'province_id' => $request->province_id,

        ]);
        return redirect()->route('admin.all.province')->with('success', 'แก้ไขอำเภอเรียบร้อยแล้ว!');
    } // End Method 

    public function AdminSubdistrictUpdate(Request $request)
    {
        $id = $request->sub_district_id;
        SubDistrict::findOrFail($id)->update([
            'name_th' => $request->name_th,
            'name_en' => $request->name_en,
            'zip_code' => $request->zip_code,
            'district_id' => $request->district_id,
        ]);
        return redirect()->route('admin.all.province')->with('success', 'แก้ไขตำบลเรียบร้อยแล้ว!');
    } // End Method 

    public function AdminSubdistrictNew(Request $request)
    {
        SubDistrict::create([
            'name_th' => $request->new_name_th,
            'name_en' => $request->new_name_en,
            'zip_code' => $request->new_zip_code,
            'district_id' => $request->new_district_id,
        ]);
        return redirect()->route('admin.all.province')->with('success', 'เพื่มตำบลเรียบร้อยแล้ว!');
    } // End Method 

    public function getDistrictDetails($id)
    {
        $district = District::findOrFail($id);
        return response()->json([
            'name_th' => $district->name_th,
            'name_en' => $district->name_en,
            'district_id' => $district->id,
            'province_id' => $district->province_id,
        ]);
    }

    public function getSubdistricts($district_id)
    {
        $subdistricts = SubDistrict::where('district_id', $district_id)->get();
        return response()->json($subdistricts);
    }

    public function getSubdistrictDetails($id)
    {
        $subdistrict = SubDistrict::findOrFail($id);

        return response()->json([
            'name_th' => $subdistrict->name_th,
            'name_en' => $subdistrict->name_en,
            'district_id' => $subdistrict->district_id,
            'zip_code' => $subdistrict->zip_code,
            'sub_district_id' => $subdistrict->id,
        ]);
    }

    public function AdminDeleteSubdistrict($id)
    {
        SubDistrict::findOrFail($id)->delete();
        return redirect()->route('admin.all.province')->with('success', 'ลบตำบลเรียบร้อยแล้ว!');
    }


    public function SeoSetting()
    {
        $setting = Seo::find(1);
        return view('admin.setting.seo', compact('setting'));
    } // End Method


    public function UpdateSeoSetting(Request $request)
    {

        $smtp = Seo::findOrFail($request->id);
        $smtp->update([
            'description' => $request->description,
            'keywords' => $request->keywords,
            'title1' => $request->title1,
            'title2' => $request->title2,
            'title3' => $request->title3,
            'adsense' => $request->adsense,
            'adsense_headtag' => $request->adsense_headtag,
        ]);

        return redirect()->back()->with('success', 'SEO settings updated successfully.');
    } // End Method
}
