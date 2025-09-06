<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Gallery;
use App\Models\PropertyType;
use App\Models\Amenity;
use App\Models\PropertyDetails;
use App\Models\Purpose;
use App\Models\PropertyLocation;
use App\Models\PropertyAmenity;
use App\Models\PropertyPrice;
use App\Models\Province;
use App\Models\District;
use App\Models\User;
use App\Models\SubDistrict;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\File;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminPropertyController extends Controller
{


    public function AdminAllType()
    {
        $ptype = PropertyType::all();
        return view('admin.property.type.all_type', compact('ptype'));
    } // End method

    public function AdminAddType()
    {
        return view('admin.property.type.add_type');
    } // End method

    public function AdminEditType($id)
    {
        $ptype = PropertyType::findOrFail($id);
        return view('admin.property.type.edit_type', compact('ptype'));
    } // End method

    public function AdminTypeStore(Request $request)
    {

        PropertyType::insert([
            'type_name' => $request->type_name,
            'type_icon' => $request->type_icon,
            'slug'      => $request->slug,
        ]);

        return redirect()->route('admin.all.type')->with('success', 'เพิ่มประเภทเรียบร้อยแล้ว!');
    }

    public function AdminTypeUpdate(Request $request)
    {

        $pid = $request->id;
        PropertyType::findOrFail($pid)->update([

            'type_name' => $request->type_name,
            'type_icon' => $request->type_icon,
            'slug'      => $request->slug,
        ]);
        return redirect()->route('admin.all.type')->with('success', 'แก้ไขประเภทเรียบร้อยแล้ว!');
    } // End Method 

    public function AdminDeleteType($id)
    {
        PropertyType::findOrFail($id)->delete();
        return redirect()->route('admin.all.type')->with('success', 'ลบประเภทเรียบร้อยแล้ว!');
    } // End Method 




    public function AdminAllAmenity()
    {
        $pamen = Amenity::all();
        return view('admin.property.amenity.all_amenity', compact('pamen'));
    } // End method

    public function AdminAddAmenity()
    {
        return view('admin.property.amenity.add_amenity');
    } // End method

    public function AdminEditAmenity($id)
    {
        $pamen = Amenity::findOrFail($id);
        return view('admin.property.amenity.edit_amenity', compact('pamen'));
    } // End method

    public function AdminStoreAmenity(Request $request)
    {
        Amenity::insert([
            'amenity_name' => $request->amenity_name,
            'amenity_icon' => $request->amenity_icon,

        ]);
        return redirect()->route('admin.all.amenity')->with('success', 'เพิ่มเครื่องอำนวยความสะดวกเรียบร้อยแล้ว!');
    }

    public function AdminUpdateAmenity(Request $request)
    {
        $pid = $request->id;
        Amenity::findOrFail($pid)->update([

            'amenity_name' => $request->amenity_name,
            'amenity_icon' => $request->amenity_icon,
        ]);
        return redirect()->route('admin.all.amenity')->with('success', 'แก้ไขเครื่องอำนวยความสะดวกเรียบร้อยแล้ว!');
    } // End Method 

    public function AdminDeleteAmenity($id)
    {
        Amenity::findOrFail($id)->delete();
        return redirect()->route('admin.all.amenity')->with('success', 'ลบเครื่องอำนวยความสะดวกเรียบร้อยแล้ว!');
    } // End Method 






    public function AdminAllProperty()
    {
        $prop = Property::latest()->limit(5)->get();
        return view('admin.property.all_property', compact('prop'));
    } // End method


    public function AdminSearchById(Request $request)
    {
        $property = Property::with(['type', 'purpose', 'detail'])->find($request->id);
        if ($property) {
            return response()->json([
                'success' => true,
                'item' => [
                    'img' => $property->detail->cover_photo ?? '',
                    'id' => $property->id,
                    'type' => $property->type->type_name ?? '',
                    'status' => $property->purpose->purpose_name ?? '',
                    'name' => $property->detail->property_name ?? '',
                    'slug' => $property->detail->property_slug ?? '',
                    'owner' => $property->owner->id ?? '',
                ]
            ]);
        }
        return response()->json(['success' => false]);
    }

    public function AdminEditProperty($id)
    {
        $property = Property::with(['detail', 'location', 'amenities', 'price'])
            ->findOrFail($id);

        $propertytype = PropertyType::get();
        $amenities = Amenity::get();
        $purposes = Purpose::get();
        $provinces = Province::get();
        $districts = District::where('province_id', $property->location->province_id)->get();
        $subdistricts = SubDistrict::where('district_id', $property->location->district_id)->get();
        $selectedAmenities = $property->amenities->pluck('id')->toArray();
        $gallery = Gallery::where('property_id', $id)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($img) => [
                'id' => $img->id,
                'url' => asset("upload/property/{$id}/{$img->filename}"),
                'is_cover' => $img->is_cover,
            ]);

        return view('admin.property.edit_property', compact(
            'property',
            'propertytype',
            'amenities',
            'purposes',
            'provinces',
            'districts',
            'subdistricts',
            'selectedAmenities',
            'gallery'
        ));
    }

    private function removeEmojis($text)
    {
        return preg_replace('/[\x{1F600}-\x{1F64F}|\x{1F300}-\x{1F5FF}|\x{1F680}-\x{1F6FF}|\x{1F1E0}-\x{1F1FF}|\x{2600}-\x{26FF}|\x{2700}-\x{27BF}]/u', '', $text);
    }

    public function AdminUpdateProperty(Request $request)
    {
        $property = Property::findOrFail($request->property_id);
        $status = $request->has('status') ? 1 : 0;
        $property->update([
            'property_type_id' => $request->property_type_id,
            'property_status' => $request->property_status,
            'property_built_year' => $request->property_built_year,
            'status' => $status,
            'expire_date' => $request->expire_date,
        ]);

        $property->location()->update([
            'property_address' => $request->property_address,
            'sub_district_id' => $request->sub_district_name,
            'district_id' => $request->district_name,
            'province_id' => $request->province_name,
            'postal_code' => $request->zip_code,
            'lat' => $request->lat,
            'lon' => $request->lon,
            'zoom_level' => $request->zoom,

        ]);

        // Update amenities
        if ($request->has('amenities_id')) {
            $amenities = $request->input('amenities_id', []);
            $property->amenities()->sync($amenities);
        }

        // Update property details
        $cleanName = $this->removeEmojis($request->property_name);
        $tag = implode(',', [
            Province::find($request->province_name)->name_th ?? '',
            District::find($request->district_name)->name_th ?? '',
            SubDistrict::find($request->sub_district_name)->name_th ?? ''
        ]);

        $cover_photo_file = Gallery::where('property_id', $request->property_id)
            ->where('is_cover', true)
            ->value('filename');
        $cover_photo_path = "upload/property/" . $request->property_id . "/" . $cover_photo_file;

        $property->detail()->update([
            'property_name' => $cleanName,
            'property_slug' => strtolower(str_replace(' ', '-', $cleanName)),
            'property_tag' => $tag,
            'long_descp' => Purifier::clean($request->long_descp),
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'land_size' => $request->land_size,
            'usage_size' => $request->usage_size,
            'cover_photo' => $cover_photo_path,

        ]);

        // Update Price
        $sell = preg_replace('/[^\d]/', '', $request->sell_price);
        $rent = preg_replace('/[^\d]/', '', $request->rent_price);
        if ($request->property_status == "1") {
            $rent = 0;
        } elseif ($request->property_status == "2") {
            $sell = 0;
        }
        $property->price()->update([
            'sell_price' => $sell,
            'rent_price' => $rent,

        ]);

        return redirect()->route('admin.all.property')->with('success', 'แก้ไขประกาศเรียบร้อยแล้ว!');
    }

    public function AdminAddProperty()
    {
        $propertytype = PropertyType::get();
        $amenities = Amenity::get();
        $purposes = Purpose::get();
        $provinces = Province::get();
        return view('admin.property.add_property', compact('propertytype', 'amenities', 'purposes', 'provinces'));
    } // End Method 

    public function AdminStoreProperty(Request $request)
    {
        try {
            $owner_id = Auth::id();
            if ($request->owner_name) {
                $owner_id = User::insertGetId([
                    'name' => $request->owner_name,
                    'email' => $request->owner_email,
                    'phone' => $request->owner_phone,
                    'password' => Hash::make('111'),
                    'uuid' => (string) Str::uuid(),
                    'created_at' => now(),

                ]);
            }

            $tempId = $request->input('temp_id');
            $pcode = IdGenerator::generate(['table' => 'properties', 'field' => 'property_code', 'length' => 5, 'prefix' => 'B']);

            $property_id = Property::insertGetId([
                'property_code' => $pcode,
                'property_type_id' => $request->property_type_id,
                'user_id' => $owner_id,
                'property_status' => $request->property_status,
                'property_built_year' => $request->property_built_year,
                'is_featured' => 0,
                'status' => 1,
                'created_at' => now(),
                'expire_date' => now()->addYear(), // 1 year from today
            ]);

            PropertyLocation::insert([
                'property_id' => $property_id,
                'property_address' => $request->property_address,
                'sub_district_id' => $request->sub_district_name,
                'district_id' => $request->district_name,
                'province_id' => $request->province_name,
                'postal_code' => $request->zip_code,
                'lat' => $request->lat,
                'lon' => $request->lon,
                'zoom_level' => $request->zoom,
                'created_at' => now(),
            ]);

            // Assuming you get amenities_id as an array from the request
            $amenities = $request->input('amenities_id', []); // default empty array
            $amenityData = [];
            foreach ($amenities as $amenityId) {
                $amenityData[] = [
                    'property_id' => $property_id,
                    'amenity_id' => $amenityId,
                    'created_at' => now(),    // add timestamps if you use them

                ];
            }
            PropertyAmenity::insert($amenityData);

            $cover_photo_file = Gallery::where('property_id', $tempId)
                ->where('is_cover', true)
                ->value('filename');
            $cover_photo_path = "upload/property/" . $property_id . "/" . $cover_photo_file;

            //---------------If not Home, Condo-------------
            $bed = $request->bedrooms;
            $bath = $request->bathrooms;
            if ($request->property_type_id == "3" || $request->property_type_id == "4") {
                $bed = "";
                $bath = "";
            }
            $tag = $request->province_name . ',' . $request->district_name . ',' . $request->sub_district_name;
            $cleanName = $this->removeEmojis($request->property_name);

            PropertyDetails::insert([
                'property_id' => $property_id,
                'bedrooms' => $bed,
                'bathrooms' => $bath,
                'land_size' => $request->land_size,
                'usage_size' => $request->usage_size,
                'property_name' => $cleanName,
                'property_slug' => strtolower(str_replace(' ', '-', $cleanName)),
                'property_tag' => $tag, //----------- FIX LATER -------------
                'long_descp' => Purifier::clean($request->long_descp),
                'cover_photo' => $cover_photo_path,
                'created_at' => now(),
            ]);

            $sell = preg_replace('/[^\d]/', '', $request->sell_price);
            $rent = preg_replace('/[^\d]/', '', $request->rent_price);
            if ($request->property_status == "1") {
                $rent = 0;
            } elseif ($request->property_status == "2") {
                $sell = 0;
            }
            PropertyPrice::insert([
                'property_id' => $property_id,
                'sell_price' => $sell,
                'rent_price' => $rent,
                'created_at' => now(),
            ]);

            //======= Update Property Id on Gallery & Folder ==========

            $old_folder = 'upload/property/' . $tempId;
            $new_folder = 'upload/property/' . $property_id;

            if (File::exists(public_path($old_folder))) {
                File::moveDirectory(public_path($old_folder), public_path($new_folder));

                Gallery::where('property_id', $tempId)->update([
                    'property_id' => $property_id,
                ]);
            }


            return redirect()->route('admin.all.property')->with('success', 'ลงประกาศอสังหาริมทรัพย์เรียบร้อยแล้ว!');
        } catch (\Throwable $e) {


            // Optionally log or report the error
            // Log::error($e);

            return back()->withErrors('เกิดข้อผิดพลาดในการบันทึกข้อมูล')->withInput();
        }
    } // End Method 

    public function AdminDeleteProperty($id)
    {
        DB::beginTransaction();

        try {
            $property = Property::findOrFail($id);

            // Manually delete related models
            PropertyLocation::where('property_id', $id)->delete();
            PropertyAmenity::where('property_id', $id)->delete();
            PropertyDetails::where('property_id', $id)->delete();
            PropertyPrice::where('property_id', $id)->delete();
            Gallery::where('property_id', $id)->delete();

            // === Delete folder (e.g., public/upload/property/{id}) ===
            $folderPath = public_path("upload/property/$id");
            if (File::exists($folderPath)) {
                File::deleteDirectory($folderPath);
            }

            // Delete the main property last
            $property->delete();

            DB::commit();

            return redirect()->route('admin.all.property')->with('success', 'ลบประกาศเรียบร้อยแล้ว!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage());
        }
    }
}