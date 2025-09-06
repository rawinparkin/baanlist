<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Auth;
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
use App\Models\Seo;
use App\Models\SiteSetting;
use App\Models\UserPlan;
use App\Models\SubDistrict;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{


    public function AddListing()
    {
        $propertytype = PropertyType::get();
        $amenities = Amenity::get();
        $purposes = Purpose::get();
        $provinces = Province::get();
        $seo = Seo::first();
        $setting = SiteSetting::first();
        return view('frontend.dashboard.add_listing', compact('propertytype', 'amenities', 'purposes', 'provinces', 'seo', 'setting'));
    } // End Method 


    public function StoreProperty(Request $request)
    {

        try {
            $tempId = $request->input('temp_id');
            $pcode = IdGenerator::generate(['table' => 'properties', 'field' => 'property_code', 'length' => 5, 'prefix' => 'B']);
            $expire_date = UserPlan::where('user_id', Auth::id())
                ->where('status', 'active')
                ->where('expire_date', '>', now())
                ->orderBy('expire_date', 'desc')
                ->value('expire_date');

            $property_id = Property::insertGetId([
                'property_code' => $pcode,
                'property_type_id' => $request->property_type_id,
                'user_id' => Auth::id(),
                'property_status' => $request->property_status,
                'property_built_year' => $request->property_built_year,
                'is_featured' => 0,
                'status' => 1,
                'expire_date' => $expire_date,
                'created_at' => now(),
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

            $user = Auth::user();
            if ($user->credit > 0) {
                User::where('id', $user->id)->decrement('credit', 1);
            }

            return redirect()->route('user.my.listing', ['status' => 'active'])->with([
                'notification' => 'ลงประกาศอสังหาริมทรัพย์เรียบร้อยแล้ว!',
                'notification_class' => 'success'
            ]);
        } catch (\Throwable $e) {
            Log::error('Property Store Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Decrease user's credit by 1

            return back()->withErrors('เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage())->withInput();
        }
    } // End Method 


    private function removeEmojis($text)
    {
        return preg_replace('/[\x{1F600}-\x{1F64F}|\x{1F300}-\x{1F5FF}|\x{1F680}-\x{1F6FF}|\x{1F1E0}-\x{1F1FF}|\x{2600}-\x{26FF}|\x{2700}-\x{27BF}]/u', '', $text);
    }

    public function UploadGallery(Request $request)
    {

        $tempId = $request->input('temp_id');

        $request->validate([
            'images.*' => 'file|mimes:jpeg,png,jpg,gif,svg,heic|max:51200',
        ]);

        $files = $request->file('images') ?? [];
        $orders = $request->input('orders', []);
        $covers = $request->input('cover', []);

        if (empty($files)) {
            return response()->json(['error' => 'No images uploaded'], 422);
        }

        // ✅ Image count validation
        $currentCount = Gallery::where('property_id', $tempId)->count();
        $incomingCount = count($files);

        if (($currentCount + $incomingCount) > 15) {
            return response()->json([
                'error' => 'ลงรูปได้สูงสุด 14 รูปต่อประกาศ',
            ], 422);
        }

        $saveDir = public_path('upload/property/' . $tempId);
        if (!is_dir($saveDir)) mkdir($saveDir, 0777, true);

        $manager = new ImageManager(new Driver());
        $savedFiles = [];
        $failedFiles = [];

        foreach ($files as $i => $file) {
            try {
                //$filename = Str::uuid() . '.jpg';
                $filename = Str::uuid() . '.webp';
                $savePath = $saveDir . '/' . $filename;
                $ext = strtolower($file->getClientOriginalExtension());
                $isCover = !empty($covers[$i]);
                $order = $orders[$i] ?? $i;

                if ($ext === 'heic') {
                    $this->ProcessHeic($file, $savePath);
                } else {
                    $this->ProcessImage($file, $savePath, $manager);
                }

                // ✅ Save thumbnail version
                $thumbnailDir = $saveDir . '/thumbnails';
                if (!is_dir($thumbnailDir)) mkdir($thumbnailDir, 0777, true);

                $thumbnailPath = $thumbnailDir . '/' . $filename;

                $thumbnail = $manager->read($savePath);
                $thumbnail->scaleDown(1080, 1080);
                $thumbnail->toWebp(45)->save($thumbnailPath); // lower quality to reduce size


                Gallery::create([
                    'property_id' => $tempId,
                    'filename' => $filename,
                    'is_cover' => $isCover,
                    'sort_order' => $order,
                    'created_at' => now(),
                ]);

                $savedFiles[] = compact('filename', 'isCover', 'order');
            } catch (\Throwable $e) {
                Log::error("Image processing failed: " . $e->getMessage());
                $failedFiles[] = ['name' => $file->getClientOriginalName(), 'error' => $e->getMessage()];
            }
        }

        return response()->json(['status' => 'ok', 'saved' => $savedFiles, 'errors' => $failedFiles]);
    }

    private function ProcessHeic($file, string $savePath): void
    {
        $inputPath = escapeshellarg($file->getRealPath());
        $savePathEscaped = escapeshellarg($savePath);

        exec("magick identify -format '%w %h' $inputPath", $dimOutput, $identifyReturn);
        if ($identifyReturn !== 0 || empty($dimOutput)) {
            throw new \Exception("Could not get dimensions of HEIC image.");
        }

        [$width, $height] = array_map('intval', explode(' ', trim($dimOutput[0])));
        $resize = $height > $width && $height > 1920
            ? '-resize x1920'
            : (($width >= $height && $width > 1920) ? '-resize 1920' : '');


        // $resize = $height > $width && $height > 3000
        //     ? '-resize x3000'
        //     : (($width >= $height && $width > 3000) ? '-resize 3000x' : '');

        //exec("magick $inputPath $resize -quality 90 $savePathEscaped", $output, $returnVar);
        exec("magick $inputPath $resize -quality 85 -define webp:method=6 $savePathEscaped", $output, $returnVar);

        if ($returnVar !== 0) {
            $errorOutput = implode("\n", $output);
            if (str_contains($errorOutput, 'no decode delegate')) {
                throw new \Exception("HEIC support missing. Install libheif.");
            }
            if (str_contains($errorOutput, 'improper image header')) {
                throw new \Exception("HEIC file appears corrupted.");
            }
            throw new \Exception("ImageMagick failed: " . $errorOutput);
        }
    }

    private function ProcessImage($file, string $savePath, ImageManager $manager): void
    {
        $image = $manager->read($file->getRealPath());
        $width = $image->width();
        $height = $image->height();

        // if ($height > $width && $height > 3000) {
        //     $image->scaleDown(height: 3000);
        // } elseif ($width >= $height && $width > 3000) {
        //     $image->scaleDown(width: 3000);
        // }

        if ($height > $width && $height > 1920) {
            $image->scaleDown(height: 1920);
        } elseif ($width >= $height && $width > 1920) {
            $image->scaleDown(width: 1920);
        }

        //$image->save($savePath, quality: 90);
        $image->toWebp(60)->save($savePath);
    }

    public function ListGallery(Request $request)
    {
        $tempId = $request->query('temp_id');
        $gallery = Gallery::where('property_id', $tempId)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($img) => [
                'id' => $img->id,
                'url' => asset("upload/property/{$tempId}/{$img->filename}"),
                'is_cover' => $img->is_cover,
            ]);

        return response()->json($gallery);
    }

    public function DeletePhoto(Request $request, $id)
    {
        $tempId = $request->query('temp_id');
        $image = Gallery::findOrFail($id);
        $filePath = public_path("upload/property/{$tempId}/{$image->filename}");
        if (File::exists($filePath)) File::delete($filePath);
        $image->delete();
        return response()->json(['success' => true]);
    }

    public function Reorder(Request $request)
    {
        $orderData = $request->input('order');
        if (!is_array($orderData)) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        foreach ($orderData as $item) {
            Gallery::where('id', $item['id'])->update(['sort_order' => $item['order']]);
        }

        return response()->json(['status' => 'success']);
    }

    public function SetCover(Request $request)
    {
        $request->validate(['id' => 'required|exists:galleries,id']);
        $image = Gallery::findOrFail($request->id);

        Gallery::where('property_id', $image->property_id)->update(['is_cover' => false]);

        $image->is_cover = true;
        $image->save();

        return response()->json(['status' => 'success']);
    }

    public function getDistricts($province_id)
    {
        return response()->json(
            District::where('province_id', $province_id)->get(['id', 'name_th'])
        );
    }

    public function getSubDistricts($district_id)
    {
        return response()->json(
            SubDistrict::where('district_id', $district_id)->get(['id', 'name_th'])
        );
    }

    public function checkAddressServer(Request $request)
    {

        $data = $request->all(); // changed from json()->all() to all()
        $rawSubDistrict = $data['sub_district'] ?? '';
        $zipCode = $data['zip_code'] ?? '';


        // Clean sub-district text (remove แขวง/ตำบล)
        $subDistrict = preg_replace('/^.*?(?:แขวง|ตำบล)/u', '', $rawSubDistrict);

        // Try to find sub-district with name match
        $sub = SubDistrict::where('zip_code', $zipCode)
            ->when($subDistrict, function ($query) use ($subDistrict) {
                $query->where(function ($q) use ($subDistrict) {
                    $q->where('name_th', 'LIKE', "%$subDistrict%")
                        ->orWhere('name_en', 'LIKE', "%$subDistrict%");
                });
            })
            ->first();

        // Fallback: try by zip code only if no name match
        if (!$sub) {
            $sub = SubDistrict::where('zip_code', $zipCode)->first();
        }

        if ($sub) {
            $district = District::find($sub->district_id);
            $province = Province::find($district->province_id);

            $districts = District::where('province_id', $province->id)->get(['id', 'name_th']);
            $subdistricts = SubDistrict::where('district_id', $district->id)->get(['id', 'name_th']);

            return response()->json([
                'exists' => true,
                'province' => $province,
                'district' => $district,
                'sub_district' => $sub,
                'districts' => $districts,
                'subdistricts' => $subdistricts,
            ]);
        }
        return response()->json(['exists' => false]);
    }

    public function EditListing($id)
    {
        $property = Property::with(['detail', 'location', 'amenities', 'price'])
            ->findOrFail($id);

        // 🔒 Check if current user owns this property
        if ((int) $property->user_id !== (int) Auth::id()) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงรายการนี้');
        }

        $propertytype = PropertyType::get();
        $amenities = Amenity::get();
        $purposes = Purpose::get();
        $provinces = Province::get();
        $districts = District::where('province_id', $property->location->province_id)->get();
        $subdistricts = SubDistrict::where('district_id', $property->location->district_id)->get();
        $selectedAmenities = $property->amenities->pluck('id')->toArray();
        $seo = Seo::first();
        $setting = SiteSetting::first();
        $gallery = Gallery::where('property_id', $id)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($img) => [
                'id' => $img->id,
                'url' => asset("upload/property/{$id}/{$img->filename}"),
                'is_cover' => $img->is_cover,
            ]);

        return view('frontend.dashboard.edit_listing', compact(
            'property',
            'propertytype',
            'amenities',
            'purposes',
            'provinces',
            'districts',
            'subdistricts',
            'selectedAmenities',
            'gallery',
            'seo',
            'setting'
        ));
    }

    public function UpdateProperty(Request $request)
    {


        $property = Property::findOrFail($request->property_id);
        // ❌ Prevent unauthorized profile updates
        if (Auth::id() != $property->user_id) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขประกาศนี้');
        }


        $property->update([
            'property_type_id' => $request->property_type_id,
            'property_status' => $request->property_status,
            'property_built_year' => $request->property_built_year,
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

        return redirect()->route('user.my.listing', ['status' => 'active'])->with([
            'notification' => 'แก้ไขประกาศเรียบร้อยแล้ว!',
            'notification_class' => 'success'
        ]);
    }

    public function DeleteProperty($id)
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

            // Check if credit still valid then update
            $credit_valid = UserPlan::where('user_id', Auth::id())
                ->where('status', 'active')
                ->where('expire_date', '>', now())
                ->get();
            if (!$credit_valid->isEmpty()) {
                $sum_credit = $credit_valid->sum('credit');
                $current_online = Property::where('user_id', Auth::id())->where('status', '1')->count();
                $available_credit = max($sum_credit - $current_online, 0);

                User::where('id', Auth::id())->update(['credit' => $available_credit]);
            }

            DB::commit();


            return redirect()->route('user.my.listing', ['status' => 'active'])->with([
                'notification' => 'ลบประกาศเรียบร้อยแล้ว!',
                'notification_class' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage());
        }
    }

    //------------- Fix Add Thumbnails for Existed Property ------------
    public function AddThumbnails()
    {
        $manager = new ImageManager(new Driver());

        $galleries = \App\Models\Gallery::all();
        $count = 0;

        foreach ($galleries as $image) {
            $propertyId = $image->property_id;
            $filename = $image->filename;

            $originalPath = public_path("upload/property/{$propertyId}/{$filename}");
            $thumbnailDir = public_path("upload/property/{$propertyId}/thumbnails");
            $thumbnailPath = "{$thumbnailDir}/{$filename}";

            if (!File::exists($originalPath)) {
                continue; // Skip if original file missing
            }

            if (!File::exists($thumbnailDir)) {
                File::makeDirectory($thumbnailDir, 0777, true);
            }

            try {
                $img = $manager->read($originalPath);
                $img->scaleDown(400, 400)->toWebp()->save($thumbnailPath);
                $count++;
            } catch (\Throwable $e) {
                Log::error("Failed to create thumbnail for: {$originalPath}. Error: " . $e->getMessage());
            }
        }

        return response()->json(['success' => "Thumbnails created: {$count} images."]);
    }
}