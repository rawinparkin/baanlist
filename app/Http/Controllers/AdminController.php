<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\UserPlan;
use App\Models\SiteSetting;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        $id = Auth::user()->id;
        $userData = User::find($id);
        $property = Property::count();
        $users = User::count();
        $blog = BlogPost::count();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonthDate = Carbon::now()->subMonth();
        $lastMonth = $lastMonthDate->month;
        $lastMonthYear = $lastMonthDate->year;

        $income1 = UserPlan::whereMonth('activated_at', $currentMonth)
            ->whereYear('activated_at', $currentYear)
            ->get();

        $income5 = UserPlan::whereDate('activated_at', Carbon::today())->get();

        $income3 = UserPlan::whereYear('activated_at', $currentYear)
            ->get();

        $income4 = UserPlan::all();
        $setting = SiteSetting::first();

        $userToday = User::whereDate('created_at', Carbon::today())->count();
        $propertyToday = Property::whereDate('created_at', Carbon::today())->count();

        return view('admin.admin_dashboard', compact('userData', 'property', 'users', 'blog', 'income1', 'income3', 'income4', 'income5', 'setting', 'userToday', 'propertyToday'));
    } // End method

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    } // End method

    public function AdminAllUser()
    {
        $users = User::latest()->get();
        return view('admin.user.all_user', compact('users'));
    } // End method

    public function AdminEditUser($id)
    {
        $userData = User::findOrFail($id);
        return view('admin.user.edit_user', compact('userData'));
    } // End method

    public function AdminUpdateUser(Request $request)
    {
        $id = $request->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->phone = preg_replace('/\D/', '', $request->phone);
        $data->line = $request->line;
        $data->credit = $request->credit;
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
            'notification' => 'อัปเดตเรียบร้อยแล้ว!',
            'notification_class' => 'success'
        ]);
    } // End method

    public function AdminUpdateUserPassword(Request $request)
    {

        $user = User::find($request->user_id);
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

    public function AdminDeleteUser($id)
    {
        $user = User::findOrFail($id);
        // Path to the user's folder
        $userFolder = public_path('upload/users/' . $id);
        // Delete entire folder if it exists
        if (File::exists($userFolder)) {
            File::deleteDirectory($userFolder);
        }
        // Delete user record
        $user->delete();
        return redirect()->route('admin.all.user')->with('success', 'ลบสมาชิกเรียบร้อยแล้ว!');
    }

    public function AdminSearchUser(Request $request)
    {
        $input = trim($request->id);

        if (!$input) {
            return response()->json([
                'success' => false,
                'message' => 'กรุณากรอก UID หรือ Email'
            ]);
        }
        // Determine if it's UID or Email
        $user = is_numeric($input)
            ? User::find($input)
            : User::where('email', $input)->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'item' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo ?? '',
                ]
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'ไม่พบสมาชิกที่ค้นหา'
        ]);
    }
}
