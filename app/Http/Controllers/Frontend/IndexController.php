<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyLocation;
use App\Models\Gallery;
use App\Models\Amenity;
use App\Models\PropertyAmenity;
use App\Models\PropertyDetails;
use App\Models\PropertyPrice;
use App\Models\User;
use App\Models\PackagePlan;
use App\Models\SiteSetting;
use App\Models\ContactBox;
use App\Models\Purpose;
use App\Models\Seo;

use App\Models\PropertyType;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use OmiseCharge;

class IndexController extends Controller
{
    public function SearchProperty(Request $request)
    {
        $lat = $request->lat;
        $lon = $request->lon;
        $label = $request->label;
        $category = $request->category;
        $purpose = $request->purpose;
        $radius = 10; // initial radius
        $selectedAmenities = $request->input('amenities', []);
        $min_price =  $request->min_price;
        $max_price = $request->max_price;



        $title = "ค้นหาอสังหาริมทรัพย์ - " . $label;
        if (!empty($category)) {
            $title = Purpose::where('id', $purpose)->first()->purpose_name . "" . PropertyType::where('id', $category)->first()->type_name . " " . $label . "";
        }

        // Get all properties within radius (limit 400) for price range calculation
        $allProps = $this->getPropertiesWithinRadius($lat, $lon, $radius, $category, $purpose, $selectedAmenities, $min_price, $max_price, false, 400);
        [$absoluteMinPrice, $absoluteMaxPrice] = $this->getMinMaxPrices(
            $this->getPropertiesWithinRadius($lat, $lon, $radius, $category, $purpose, $selectedAmenities, null, null, false, 400),
            $purpose
        );
        // If no properties, increase radius and try again
        if ($allProps->isEmpty()) {
            $radius = 60;
            $allProps = $this->getPropertiesWithinRadius($lat, $lon, $radius, $category, $purpose, $selectedAmenities, $min_price, $max_price, false, 400);
            [$absoluteMinPrice, $absoluteMaxPrice] = $this->getMinMaxPrices(
                $this->getPropertiesWithinRadius($lat, $lon, $radius, $category, $purpose, $selectedAmenities, null, null, false, 400),
                $purpose
            );
        }

        // Get min and max prices based on purpose
        [$minPrice, $maxPrice] = $this->getMinMaxPrices($allProps, $purpose);
        // These are for setting the slider's min and max range (based on all props before filtering)


        // Manually paginate the $allProps collection for UI (2 per page, or whatever you want)
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $allProps->slice(($currentPage - 1) * $perPage, $perPage)->values();


        $props = new LengthAwarePaginator(
            $currentPageItems,
            $allProps->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );


        // Prepare locations for JS
        $maplocations = $props->map(function ($property, $index) {
            return [
                'url' => route('property.details', [
                    'id' => $property->id,
                    'slug' => $property->detail->property_slug,
                ]),
                'image' => asset(dirname($property->detail->cover_photo) . '/thumbnails/' . basename($property->detail->cover_photo)),
                'price' => '฿' . number_format(
                    $property->property_status == 2
                        ? $property->price->rent_price
                        : $property->price->sell_price
                ),
                'room' => $property->detail->bedrooms . ' นอน | '  . $property->detail->bathrooms . ' น้ำ',
                'lat' => $property->location->lat ?? 0,
                'lng' => $property->location->lon ?? 0,
                'index' => $index + 1,
                'icon' => '<i class="fa fa-home"></i>',
            ];
        });
        $purposes = Purpose::all();
        $propertytype = PropertyType::all();
        $amenities = Amenity::all();
        $setting = SiteSetting::first();
        $seo = Seo::first();

        //-------------------------- AJAX---------------------------

        if ($request->ajax()) {
            $listings_html = view('frontend.property.components.listings', [
                'props' => $props,
                'setting' => $setting,
                'seo' => $seo,
            ])->render();

            $maplocations = $props->map(function ($property, $index) {
                return [
                    'url' => route('property.details', [
                        'id' => $property->id,
                        'slug' => $property->detail->property_slug,
                    ]),
                    'image' => asset(dirname($property->detail->cover_photo) . '/thumbnails/' . basename($property->detail->cover_photo)),
                    'price' => '฿' . number_format(
                        $property->property_status == 2
                            ? $property->price->rent_price
                            : $property->price->sell_price
                    ),
                    'room' => $property->detail->bedrooms . ' นอน | '  . $property->detail->bathrooms . ' น้ำ',
                    'lat' => $property->location->lat ?? 0,
                    'lng' => $property->location->lon ?? 0,
                    'index' => $index + 1,
                    'icon' => '<i class="fa fa-home"></i>',
                ];
            })->toArray();

            return response()->json([
                'listings_html' => $listings_html,
                'maplocations' => $maplocations,
                'label' => $label,
                'total' => $props->total(),
            ]);
        }

        // --------------------- END AJAX ---------------------------

        return view('frontend.property.property_search', compact('props', 'setting', 'lat', 'lon', 'label', 'purposes', 'propertytype', 'maplocations', 'title', 'category', 'purpose', 'amenities', 'minPrice', 'maxPrice', 'absoluteMinPrice', 'absoluteMaxPrice', 'seo'))
            ->with('radius', $radius);
    }

    private function getPropertiesWithinRadius($lat, $lon, $radius, $category = null, $purpose = null, $amenities = [], $min_price = null, $max_price = null, $paginate = true, $limit = 20)
    {
        $query = Property::with(['location', 'type', 'price', 'detail', 'galleries', 'amenities'])
            ->where('status', 1)
            ->when($category, function ($query) use ($category) {
                $query->whereHas('type', function ($query) use ($category) {
                    $query->where('id', $category);
                });
            })
            ->when($purpose, fn($q) => (
                in_array((int)$purpose, [1, 2])
                ? $q->whereIn('property_status', [(int)$purpose, 3])
                : $q->where('property_status', $purpose)
            ))

            ->when(!is_null($min_price) || !is_null($max_price), function ($query) use ($purpose, $min_price, $max_price) {
                $query->whereHas('price', function ($q) use ($purpose, $min_price, $max_price) {
                    switch ((int) $purpose) {
                        case 1: // For Sale
                            if (!is_null($min_price)) {
                                $q->where('sell_price', '>=', $min_price);
                            }
                            if (!is_null($max_price)) {
                                $q->where('sell_price', '<=', $max_price);
                            }
                            break;

                        case 2: // For Rent
                            if (!is_null($min_price)) {
                                $q->where('rent_price', '>=', $min_price);
                            }
                            if (!is_null($max_price)) {
                                $q->where('rent_price', '<=', $max_price);
                            }
                            break;
                    }
                });
            })

            ->when($lat && $lon, function ($query) use ($lat, $lon, $radius) {
                $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(lat)) * cos(radians(lon) - radians(?)) + sin(radians(?)) * sin(radians(lat))))";
                $query->whereHas('location', function ($query) use ($haversine, $lat, $lon, $radius) {
                    $query->whereRaw("$haversine < ?", [$lat, $lon, $lat, $radius]);
                });
            })

            ->when(!empty($amenities), function ($query) use ($amenities) {
                foreach ($amenities as $amenityId) {
                    $query->whereHas('amenities', function ($q) use ($amenityId) {
                        $q->where('amenities.id', $amenityId);
                    });
                }
            })
            ->orderBy('created_at', 'desc');  // <-- Add this here

        if ($paginate) {
            return $query->paginate($limit);
        } else {
            return $query->limit($limit)->get();
        }
    }


    private function getMinMaxPrices($props, $purpose)
    {
        $prices = $props->map(function ($property) use ($purpose) {
            if (!$property->price) return null;

            switch ((int) $purpose) {
                case 1: // For Sale
                    return $property->price->sell_price ?? null;
                case 2: // For Rent
                    return $property->price->rent_price ?? null;
                default: // No purpose selected — include both sell & rent prices
                    $sell = $property->price->sell_price ?? 0;
                    $rent = $property->price->rent_price ?? 0;
                    return max($sell, $rent); // or return both if you want separate filters
            }
        })->filter(); // Remove nulls or 0s

        if ($prices->isEmpty()) {
            return [0, 0];
        }

        $min = floor($prices->min() / 100) * 100;
        $max = ceil($prices->max() / 100) * 100;

        // Add buffer based on purpose
        switch ((int) $purpose) {
            case 1:
                $max += 100000;
                break;
            case 2:
                $max += 10000;
                break;
            default:
                $max += 100000; // If no purpose is chosen, assume sale buffer
                break;
        }

        return [$min, $max];
    }





    public function PropertyDetails($id, $slug)
    {
        // Eager-load everything you need
        $property = Property::with([
            'amenities',
            'detail',
            'price',
            'location.subdistrict.district.province'
        ])->findOrFail($id);

        $property->increment('views');


        $seo = Seo::first();
        $owner_data = User::find($property->user_id);
        $gallery = Gallery::where('property_id', $id)
            ->orderBy('is_cover', 'desc')
            ->get();

        $galleryUrls = $gallery->map(fn($img) => asset("upload/property/{$img->property_id}/{$img->filename}"));

        $relatedProperty = Property::where('property_type_id', $property->property_type_id)
            ->where('id', '!=', $id)
            ->where('property_status', $property->property_status)
            ->whereHas('location', function ($query) use ($property) {
                $query->where('province_id', $property->location->province_id)
                    ->where('sub_district_id', $property->location->sub_district_id);
            })
            ->with([
                'detail',
                'price',
                'location.subdistrict.district.province'
            ])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('frontend.property.property_details', [
            'property' => $property,
            'gallery' => $gallery,
            'galleryUrls' => $galleryUrls,
            'owner_data' => $owner_data,
            'relatedProperty' => $relatedProperty,
            'seo' => $seo,
        ]);
    }

    public function ShowProfile($identifier)
    {
        $user = User::where('uuid', $identifier)->orWhere('uuid', $identifier)->firstOrFail();
        $property = Property::where('user_id', $user->id)->get();
        $seo = Seo::first();
        return view('frontend.show_profile', compact('user', 'property', 'seo'));
    }

    public function ShowPackage()
    {
        $plan = PackagePlan::latest()->where('is_active', 1)->get();
        $seo = Seo::first();
        return view('frontend.package.package', compact('plan', 'seo'));
    }

    public function ShowContact()
    {
        $setting = SiteSetting::find(1);
        $seo = Seo::first();
        return view('frontend.contact', compact('setting', 'seo'));
    }

    public function StoreContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        ContactBox::create($request->only('name', 'email', 'subject', 'message'));
        return redirect()->route('show.contact')->with('success', 'ส่งข้อความเรียบร้อยแล้ว!');
    }

    public function withinBounds(Request $request)
    {
        $north = $request->input('north');
        $east = $request->input('east');
        $south = $request->input('south');
        $west = $request->input('west');

        $properties = Property::with(['location', 'price', 'detail', 'galleries'])
            ->whereBetween('latitude', [$south, $north])
            ->whereBetween('longitude', [$west, $east])
            ->get();

        $mapData = $properties->map(function ($prop, $index) {
            return [
                'url' => route('property.details', ['id' => $prop->id, 'slug' => $prop->detail->property_slug]),
                'image' => $prop->galleries->first()
                    ? asset('upload/property/' . $prop->id . '/' . $prop->galleries->first()->filename)
                    : asset('frontend/images/popular-location-04.jpg'),
                'price' => $prop->property_status == '2'
                    ? '฿' . number_format($prop->price->rent_price) . ' / เดือน'
                    : '฿' . number_format($prop->price->sell_price),
                'room' => "{$prop->detail->bedrooms} ห้องนอน | {$prop->detail->bathrooms} ห้องน้ำ | {$prop->detail->land_size} ตร.ม.",
                'lat' => $prop->latitude,
                'lng' => $prop->longitude,
                'index' => $index,
                'icon' => '<i class="fa fa-home"></i>'
            ];
        });

        return response()->json([
            'locations' => $mapData,
            'html' => view('frontend.property.partials.listings', ['props' => $properties])->render()
        ]);
    }

    public function ShowTerm()
    {
        $setting = SiteSetting::first();
        $seo = Seo::first();
        return view('frontend.term.term', compact('setting', 'seo'));
    }

    public function ShowPolicy()
    {
        $setting = SiteSetting::first();
        $seo = Seo::first();
        return view('frontend.privacy.privacy', compact('setting', 'seo'));
    }
}