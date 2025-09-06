<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function AddToWishList(Request $request, $property_id)
    {
        if (Auth::check()) {
            $exists = Wishlist::where('user_id', Auth::id())->where('property_id', $property_id)->first();
            if (!$exists) {
                Wishlist::insert([
                    'user_id' => Auth::id(),
                    'property_id' => $property_id,
                    'created_at' => now()
                ]);
                return response()->json(['success' => 'เก็บประกาศนี้แล้ว!']);
            } else {
                return response()->json(['error' => 'คุณมีประกาศนี้ในรายการโปรดแล้ว!']);
            }
        } else {
            return response()->json(['error' => 'เข้าระบบก่อน!']);
        }
    } // End Method 

    public function DestroyWishlist($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->where('id', $id)->first();

        if (!$wishlist) {
            return response()->json(['success' => false, 'message' => 'ไม่พบรายการโปรดนี้'], 404);
        }

        $wishlist->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'ลบรายการโปรดเรียบร้อยแล้ว']);
        }

        return redirect()->back()
            ->with('notification', 'ลบรายการโปรดเรียบร้อยแล้ว')
            ->with('notification_class', 'success');
    }
}