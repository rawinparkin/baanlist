<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OmiseCharge;
use App\Models\Billing;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Tax\Settings;
use App\Models\PackagePlan;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Seo;
use App\Models\UserPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{


    public function createCharge(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|string',
                'amount' => 'required|numeric|min:1',
                'card_name' => 'required|string|max:255',
            ]);
            // Get token and amount from request
            $token = $request->input('token');
            $amount = $request->input('amount');

            // Create a charge
            $charge = \OmiseCharge::create([
                'amount' => $amount,
                'currency' => 'thb',
                'card' => $token,
                'description' => 'Charge from Laravel app',
            ]);

            // Optional: save charge info to your database
            Billing::create([
                'first_name' => $request->input('card_name'),
                'last_name' => $request->input('card_name'),
                'address_line' => 1,
                'sub_district' => 1,
                'district' => 1,
                'province' => 1,
                'phone' => 1231231,
                'amount' => $amount / 100,

            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Charge successful!',
                'charge' => $charge,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function charge(Request $request)
    {
        $request->validate([
            'bank' => 'required|string',
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = $request->amount * 100; // convert to satang
        $bank = $request->bank;

        try {
            $source = \OmiseSource::create([
                'amount' => $amount,
                'currency' => 'thb',
                'type' => $bank,
            ]);

            $charge = \OmiseCharge::create([
                'amount' => $amount,
                'currency' => 'thb',
                'source' => $source['id'],
                'return_uri' => route('payment.success'), // user redirected here after payment
            ]);

            return redirect($charge['authorize_uri']); // redirect to Omise's payment page

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function promptpayCharge(Request $request)
    {
        try {
            $sourceId = $request->input('source_id');
            $amount = $request->input('amount');

            $charge = \OmiseCharge::create([
                'amount' => $amount,
                'currency' => 'THB',
                'source' => $sourceId,
            ]);

            if (isset($charge['source']['scannable_code']['image']['download_uri'])) {
                return response()->json([
                    'status' => 'success',
                    'qr' => $charge['source']['scannable_code']['image']['download_uri'],
                    'charge_id' => $charge['id'],
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'QR Code not found in response.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    //--------------------- Stripe -------------------------

    public function Checkout($id)
    {
        $selectedPlan = PackagePlan::findOrFail($id);
        $setting = SiteSetting::first();
        $user = User::find(Auth::id());
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
                    'expire_date' => now()->addDays($selectedPlan->validity_days),
                ]);
            }

            return redirect()->route('user.package.plan')
                ->with('success', 'คุณได้ ' . $selectedPlan->package_credits . ' เครดิตสำหรับลงได้ ' . $selectedPlan->package_credits . ' ประกาศ!');
        } else {

            $expiryDate = Carbon::now()->addDays((int) $selectedPlan->validity_days)->format('d/m/Y');
            $amount = $selectedPlan->price;
            return view('frontend.dashboard.package.checkout', compact('selectedPlan', 'setting', 'amount', 'seo', 'expiryDate'));
        }
    }

    public function StripeCheckout(Request $request)
    {

        DB::beginTransaction();

        try {
            $id = Auth::id();
            $packageId = $request->package_id;
            $package = PackagePlan::findOrFail($packageId);
            $userData = User::findOrFail($id);

            $request->validate([
                'stripe_payment_method_id' => 'required_if:payment_method,card',
                'package_id' => 'required|exists:package_plans,id',
                'amount' => 'required|numeric|min:1',
            ]);

            $paymentMethod = $request->payment_method;

            if ($paymentMethod === 'card') {

                Stripe::setApiKey(config('services.stripe.secret'));
                $paymentIntent = PaymentIntent::create([
                    'amount' => $request->amount * 100,
                    'currency' => 'thb',
                    'payment_method' => $request->stripe_payment_method_id,
                    'confirm' => true, // Confirm the payment immediately

                    // Use this to prevent redirects
                    'payment_method_types' => ['card'],
                ]);

                if ($paymentIntent->status === 'succeeded') {

                    // Save billing info
                    $billing_id = Billing::insertGetId([
                        'first_name' => $userData->name,
                        'card_holder_name' => $request->card_holder_name,
                        'phone' => $userData->phone,
                        'amount' => $package->price,
                        'payment_method_id' => $paymentMethod,
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

                    $credits = max(0, (int) $package->package_credits);
                    User::where('id', $id)->update([
                        'credit' => DB::raw("credit + {$credits}"),
                    ]);


                    DB::commit();

                    // ✅ Save order, update user, etc.
                    return redirect()->route('dashboard')->with('notification', 'ชำระเงินสำเร็จ! คุณได้ ' . $package->package_credits . ' เครดิตสำหรับลงประกาศ');
                } else {
                    return back()->withErrors('การชำระเงินไม่สำเร็จ กรุณาลองอีกครั้ง');
                }
            } elseif ($paymentMethod === 'promptpay') {
                // if there any code later
            } else {
                throw new \Exception('รูปแบบการชำระเงินไม่ถูกต้อง');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function generatePromptPayStripe(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:package_plans,id',
            'amount' => 'required|numeric|min:1',
        ]);

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $amount = intval($request->amount * 100); // แปลงเป็นสตางค์
            $user = Auth::user();
            $userName = $user->name ?? 'Customer';
            $userEmail = $user->email ?? 'no-reply@example.com';

            // 1. สร้าง PaymentIntent แต่ยังไม่ confirm
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'thb',
                'payment_method_types' => ['promptpay'],
                'description' => 'PromptPay for Package ID: ' . $request->package_id,
                'metadata' => [
                    'package_id' => $request->package_id,
                    'user_id' => Auth::id(),
                ],
            ]);

            // 2. Confirm PaymentIntent พร้อม billing_details และ return_url
            $paymentIntent = $paymentIntent->confirm([
                'payment_method_data' => [
                    'type' => 'promptpay',
                    'billing_details' => [
                        'name' => $userName,
                        'email' => $userEmail,
                    ],
                ],
                'return_url' => route('dashboard'),
            ]);


            // 3. Retry check QR Code (max 5 ครั้ง, รอ 1 วิระหว่างครั้ง)
            $maxAttempts = 5;
            $attempt = 0;
            $qrUrl = null;

            while ($attempt < $maxAttempts && !$qrUrl) {
                $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntent->id);
                $qrUrl = $paymentIntent->next_action->promptpay_display_qr_code->image_url ?? null;

                if (!$qrUrl) {
                    $attempt++;
                    sleep(1);
                }
            }

            if (!$qrUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code ยังไม่ถูกสร้าง กรุณาลองใหม่อีกครั้ง',
                ]);
            }

            return response()->json([
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'qr_url' => $qrUrl,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถสร้าง QR ได้: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function checkPromptPayStripe($paymentIntentId)
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

            return response()->json([
                'status' => $paymentIntent->status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function finalizePromptPayPayment(Request $request)
    {
        DB::beginTransaction();

        try {
            $id = Auth::id();
            $packageId = $request->package_id;
            $paymentIntentId = $request->payment_intent_id; // pass this from frontend
            $package = PackagePlan::findOrFail($packageId);
            $userData = User::findOrFail($id);

            if (!$paymentIntentId) {
                throw new \Exception('Missing payment_intent_id');
            }

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                // Save billing info
                $billing_id = Billing::insertGetId([
                    'first_name' => $userData->name,
                    'card_holder_name' => '', // No card holder name for PromptPay
                    'phone' => $userData->phone,
                    'amount' => $package->price,
                    'payment_method_id' => 'promptpay',
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

                $credits = max(0, (int) $package->package_credits);
                User::where('id', $id)->update([
                    'credit' => DB::raw("credit + {$credits}"),
                ]);

                DB::commit();

                return redirect()->route('dashboard')->with('notification', 'ชำระเงินสำเร็จ! คุณได้ ' . $package->package_credits . ' เครดิตสำหรับลงประกาศ');
            } else {
                DB::rollBack();
                return back()->withErrors('สถานะการชำระเงินยังไม่สมบูรณ์ กรุณาลองใหม่อีกครั้ง');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}