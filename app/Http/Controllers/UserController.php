<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\PackagePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\UserPlan;
use App\Models\Wishlist;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\SiteSetting;
use App\Models\Seo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Tax\Settings;

class UserController extends Controller
{
    public function Index()
    {
        $seo = Seo::first();

        $property = Cache::rememberForever('homepage_properties', function () {
            return Property::with([
                'detail',
                'price',
                'location.subdistrict',
                'location.province'
            ])
                ->where('status', '1')
                ->orderBy('id', 'desc')
                ->limit(16)
                ->get();
        });

        return view('frontend.index', compact('seo', 'property'));
    } // End Method

    public function UserLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    } // End Method 

    public function UserMessages()
    {
        $id = Auth::id();
        $userData = User::find($id);
        $settings = SiteSetting::first();
        $seo = Seo::first();
        return view('frontend.dashboard.message.messages', compact('userData', 'settings', 'seo'));
    } // End Method 

    public function UserProfile()
    {
        $userData = Auth::user();
        $settings = SiteSetting::first();
        $seo = Seo::first();
        return view('frontend.dashboard.profile', compact('userData', 'settings', 'seo'));
    } // End Method 

    public function UserProfileStore(Request $request)
    {
        // ❌ Prevent unauthorized profile updates
        if (Auth::id() != $request->id) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขโปรไฟล์นี้');
        }

        $id = $request->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->phone = preg_replace('/\D/', '', $request->phone);
        $data->line = $request->line;
        $data->about = $request->about;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $oldImage = $request->old_img;

            $ext = strtolower($file->getClientOriginalExtension());
            $saveDir = public_path('upload/users/' . $id);

            // Always save as .webp (unless HEIC)
            $filename = date('YmdHi') . ($ext === 'heic' ? '.jpg' : '.webp');
            $savePath = $saveDir . '/' . $filename;

            // Delete old image
            if (!empty($oldImage) && file_exists($saveDir . '/' . $oldImage)) {
                unlink($saveDir . '/' . $oldImage);
            } else {
                if (!file_exists($saveDir)) {
                    File::makeDirectory($saveDir, 0777, true);
                }
            }

            if ($ext === 'heic') {
                // Convert HEIC to WebP using ImageMagick
                $filename = date('YmdHi') . '.webp';
                $savePath = $saveDir . '/' . $filename;

                // Create folder if not exists
                if (!File::exists($saveDir)) {
                    File::makeDirectory($saveDir, 0777, true);
                }

                // Crop and convert to WebP
                $dimensions = [];
                exec("magick identify -format '%w %h' " . escapeshellarg($file->getRealPath()), $dimensions);
                list($width, $height) = explode(' ', $dimensions[0]);
                $square = min($width, $height);

                $resizeCrop = ($square >= 1000)
                    ? "-gravity center -crop {$square}x{$square}+0+0 +repage -resize 1000x1000"
                    : "-gravity center -crop {$square}x{$square}+0+0 +repage";

                exec("magick '{$file->getRealPath()}' $resizeCrop webp:'$savePath'");

                $data->photo = $filename;
            } else {
                // Convert to WebP
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);
                $image->coverDown(1000, 1000)->toWebp(90)->save($savePath); // save as WebP
                $data->photo = $filename;
            }
        }

        $data->save();
        return redirect()->back()->with([
            'notification' => 'โปรไฟล์ของคุณได้รับการอัปเดตเรียบร้อยแล้ว!',
            'notification_class' => 'success'
        ]);
    }


    public function UserPasswordUpdate(Request $request)
    {

        $user = Auth::user();

        // Check if user has a known password
        $isSocialUser = $user->google_provider_id !== null;

        $rules = [
            'new_password' => 'required|confirmed'
        ];

        if (!$isSocialUser) {
            // Require old password only if user signed up with email/password
            $rules['old_password'] = 'required|current_password';
        }

        $request->validate($rules, [
            'old_password.required' => 'กรุณากรอกรหัสผ่านเดิม',
            'old_password.current_password' => 'รหัสผ่านเดิมไม่ถูกต้อง',
            'new_password.required' => 'กรุณากรอกรหัสผ่านใหม่',
            'new_password.confirmed' => 'รหัสผ่านใหม่ไม่เหมือนกัน กรุณากรอกให้เหมือนกัน',
        ]);

        User::whereId($user->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()->with([
            'notification' => 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว!',
            'notification_class' => 'success'
        ]);
    } // End Method 

    public function MyListing(Request $request)
    {

        $id = Auth::id();
        $property = Property::where('user_id', $id)->latest()->get();
        $settings = SiteSetting::first();
        $url = "active";

        $status = $request->query('status');
        if ($status == 'active') {
            $url = "active";
        } elseif ($status == 'pending') {
            $url = "pending";
        } elseif ($status == 'expired') {
            $url = "expired";
        }
        $seo = Seo::first();

        return view('frontend.dashboard.myListing.' . $url, compact('property', 'seo'));
    }

    public function GetWishList()
    {
        $seo = Seo::first();
        $wishlist = Wishlist::with('property.detail', 'property.location')->where('user_id', Auth::id())->latest()->get();
        return view('frontend.dashboard.wishlist', compact('wishlist', 'seo'));
    }

    public function PackagePlan()
    {
        $plan = PackagePlan::latest()->where('is_active', 1)->get();
        $seo = Seo::first();
        return view('frontend.dashboard.package.buy_package', compact('plan', 'seo'));
    }

    public function PaymentHistory()
    {
        $id = Auth::id();
        $payment = UserPlan::where('user_id', $id)->orderBy('created_at', 'desc')->get()->map(function ($item) {
            // Set locale globally for consistency
            Carbon::setLocale('th');

            $activated = Carbon::parse($item->activated_at);
            $expire = Carbon::parse($item->expire_date);

            // Use translatedFormat to get Thai month abbreviation (e.g., ก.ค.)
            $item->formatted_activated_at = $activated->translatedFormat('d M') . ' ' . ($activated->year + 543);
            $item->formatted_expire_date = $expire->translatedFormat('d M') . ' ' . ($expire->year + 543);

            return $item;
        });
        $seo = Seo::first();


        return view('frontend.dashboard.package.history', compact('payment', 'seo'));
    }

    public function ChoosePlan($id)
    {
        $setting = SiteSetting::first();
        $user = User::find(Auth::id());
        $selectedPlan = PackagePlan::findOrFail($id);
        $seo = Seo::first();
        if ($id == 1) {
            // 1. Check active free plan
            $hasActiveFreePlan = UserPlan::where('user_id', $user->id)
                ->where('package_id', 1)
                ->exists();

            if ($hasActiveFreePlan) {
                return redirect()->route('user.package.plan')
                    ->with('error', 'คุณไม่สามารถเลือกแพ็คเกจฟรีได้อีก');
            }

            // No active free plan — allow new free plan use
            if ($user->credit == 0) {
                $user->credit = $selectedPlan->package_credits;
                $user->save();

                $billing_id = Billing::insertGetId([
                    'first_name' => $user->name,
                    'card_holder_name' => $user->name,
                    'phone' => $user->phone,
                    'amount' => $selectedPlan->price,
                    'created_at' => now(),
                ]);

                // Generate unique invoice
                do {
                    $invoice = 'BLI' . mt_rand(10000000, 99999999);
                } while (UserPlan::where('invoice', $invoice)->exists());
                UserPlan::create([
                    'user_id' => $user->id,
                    'package_id' => $id,
                    'billing_id' => $billing_id,
                    'paid_amount' => $selectedPlan->price,
                    'invoice' => $invoice,
                    'activated_at' => now(),
                    'expire_date' => now()->addDays((int) $selectedPlan->validity_days),
                ]);
            }

            return redirect()->route('user.package.plan')
                ->with('success', 'คุณได้ ' . $selectedPlan->package_credits . ' เครดิตสำหรับลงได้ ' . $selectedPlan->package_credits . ' ประกาศ!');
        } else {

            $expiryDate = Carbon::now()->addDays((int) $selectedPlan->validity_days)->format('d/m/Y');
            $amount = $selectedPlan->price;
            return view('frontend.dashboard.package.choose_plan', compact('selectedPlan', 'setting', 'amount', 'seo', 'expiryDate'));
        }
    }


    public function generatePromptPay(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        try {
            $amount = $request->amount * 100; // convert to satang

            $source = \OmiseSource::create([
                'amount' => $amount,
                'currency' => 'THB',
                'type' => 'promptpay',
            ]);

            $charge = \OmiseCharge::create([
                'amount' => $amount,
                'currency' => 'THB',
                'source' => $source['id'],
            ]);

            return response()->json([
                'success' => true,
                'qr_url' => $charge['source']['scannable_code']['image']['download_uri'],
                'expires_at' => $charge['source']['expires_at'] ?? null,
                'charge_id' => $charge['id'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถสร้าง QR ได้: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function StorePackagePlan(Request $request)
    {
        DB::beginTransaction();

        try {
            $id = Auth::id();
            $packageId = $request->package_id;
            $package = PackagePlan::findOrFail($packageId);
            $userData = User::findOrFail($id);

            $request->validate([
                'package_id' => 'required|exists:package_plans,id',
                'omise_token' => 'nullable|string',
                'card_holder_name' => 'required_if:payment_method,card|string|max:255',
                'creditcard' => 'nullable|string',
                'card_exp_month' => 'nullable|digits:2',
                'card_exp_year' => 'nullable|digits:2',
                'card_cvc' => 'nullable|digits_between:3,4',
                'agree' => 'required|accepted',
            ]);

            $paymentMethod = $request->payment_method;

            if ($paymentMethod === 'card') {
                if (empty($request->omise_token)) {
                    throw new \Exception('ไม่พบ Omise token สำหรับบัตรเครดิต');
                }

                \OmiseCharge::create([
                    'amount' => $package->price * 100,
                    'currency' => 'THB',
                    'card' => $request->omise_token,
                ]);
            } elseif ($paymentMethod === 'promptpay') {
                // Skip OmiseCharge; assume paid externally
                // You might want to add an admin verification step here, or queue for manual confirmation
            } else {
                throw new \Exception('รูปแบบการชำระเงินไม่ถูกต้อง');
            }


            // Save billing info
            $billing_id = Billing::insertGetId([
                'first_name' => $userData->name,
                'card_holder_name' => $request->card_holder_name,
                'phone' => $userData->phone,
                'amount' => $package->price,
                'created_at' => now(),
            ]);

            do {
                $invoice = 'BLI' . mt_rand(10000000, 99999999);
            } while (UserPlan::where('invoice', $invoice)->exists());

            UserPlan::create([
                'user_id' => $id,
                'package_id' => $packageId,
                'billing_id' => $billing_id,
                'paid_amount' => $package->price,
                'invoice' => $invoice,
                'activated_at' => now(),
                'expire_date' => now()->addDays($package->validity_days),
            ]);

            User::where('id', $id)->update([
                'credit' => DB::raw($package->package_credits . ' + credit'),
            ]);

            DB::commit();

            return redirect()->route('dashboard', ['status' => 'active'])->with([
                'notification' => 'ชำระเงินเรียบร้อยแล้ว!',
                'notification_class' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function checkPromptPayStatus(Request $request)
    {
        $packageId = $request->query('package_id');
        if (!$packageId) {
            return response()->json(['status' => 'error', 'message' => 'Missing package_id']);
        }
        $package = PackagePlan::findOrFail($packageId);
        $chargeId = $request->query('charge_id');

        if (!$chargeId) {
            return response()->json(['status' => 'error', 'message' => 'Missing charge_id']);
        }

        $testMode = $request->query('test_mode') === 'true';

        try {
            // ✅ Simulate Omise response in test mode
            if ($testMode) {
                $charge = ['status' => 'successful'];
            } else {
                $charge = \OmiseCharge::retrieve($chargeId);
            }

            if ($charge['status'] === 'successful') {
                $user = Auth::user();
                if (!$user) {
                    return response()->json(['status' => 'error', 'message' => 'User not authenticated']);
                }

                $billing_id = Billing::insertGetId([
                    'first_name' => $user->name,
                    'card_holder_name' => 'PromptPay',
                    'phone' => $user->phone,
                    'amount' => $package->price,
                    'created_at' => now(),
                ]);

                do {
                    $invoice = 'BLI' . mt_rand(10000000, 99999999);
                } while (UserPlan::where('invoice', $invoice)->exists());

                UserPlan::create([
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'billing_id' => $billing_id,
                    'paid_amount' => $package->price,
                    'invoice' => $invoice,
                    'activated_at' => now(),
                    'expire_date' => now()->addDays($package->validity_days),
                ]);

                User::where('id', $user->id)->update([
                    'credit' => DB::raw($package->package_credits . ' + credit'),
                ]);

                return response()->json([
                    'status' => 'paid',
                    'redirect_url' => route('dashboard', [
                        'success' => 'ชำระเงินเรียบร้อยแล้ว!'
                    ]),
                ]);
            }

            return response()->json(['status' => 'pending']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function UserReceipt($id)
    {
        $seo = Seo::first();
        $setting = SiteSetting::first();
        $userId = Auth::id();
        // Only fetch the record if it belongs to the logged-in user
        $receipt = UserPlan::where('id', $id)
            ->where('user_id', $userId)
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
        return view('frontend.dashboard.package.receipt', compact('receipt', 'seo', 'setting'));
    }






    public function DeleteUser($id)
    {
        $user = User::findOrFail($id);

        // Ensure user is deleting their own account
        if ($user->id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            // Loop through each property and call delete logic
            $properties = Property::where('user_id', $user->id)->get();

            $propertyController = new PropertyController();
            foreach ($properties as $property) {
                $propertyController->DeleteProperty($property->id);
            }

            // Delete user
            $user->delete();

            DB::commit();

            Auth::logout(); // log user out
            return redirect()->route('home.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการลบบัญชี: ' . $e->getMessage());
        }
    }
}