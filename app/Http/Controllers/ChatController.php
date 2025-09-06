<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Seo;

class ChatController extends Controller
{
    public function SendMsg(Request $request)
    {
        $request->validate([
            'msg' => 'required|string|max:1000',
            'receiver_id' => 'required|exists:users,id',
        ]);

        if (Auth::id() === (int) $request->receiver_id) {
            return response()->json(['message' => 'ไม่สามารถส่งข้อความถึงตัวเองได้'], 422);
        }

        ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->msg,
        ]);

        return response()->json(['message' => 'ส่งข้อความเรียบร้อยแล้ว']);
    }
    public function ChatPage()
    {
        $seo = Seo::first();
        return view('frontend.dashboard.message.chat', compact('seo'));
    }

    public function GetAllUsers()
    {
        $authId = Auth::id();

        $chats = ChatMessage::with(['sender', 'receiver'])
            ->where('sender_id', $authId)
            ->orWhere('receiver_id', $authId)
            ->latest()
            ->get();

        $userMessages = [];

        foreach ($chats as $chat) {
            // Identify the "other" user in the conversation
            $otherUser = $chat->sender_id === $authId ? $chat->receiver : $chat->sender;
            $otherUserId = $otherUser->id;

            // ✅ Skip self (just in case)
            if ($otherUserId == $authId) continue;

            // Only save the most recent chat per user
            if (!isset($userMessages[$otherUserId])) {
                $userMessages[$otherUserId] = [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'photo' => $otherUser->photo,
                    'last_message' => $chat->message,
                    'last_message_time' => $chat->created_at->diffForHumans(),
                    'is_read' => $chat->receiver_id === $authId ? $chat->is_read : 3,
                ];
            }
        }

        return response()->json(array_values($userMessages));
    }

    public function getConversation($userId)
    {
        $authId = Auth::id();

        $messages = ChatMessage::where(function ($query) use ($authId, $userId) {
            $query->where('sender_id', $authId)->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($authId, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $authId);
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    public function getAuthUserInfo()
    {
        $user = Auth::user();

        return response()->json([
            'id' => $user->id,
            'photo' => $user->photo,
        ]);
    }

    public function DeleteConversation(User $user)
    {
        ChatMessage::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', Auth::id());
        })->delete();

        return response()->json(['success' => true]);
    }

    public function markAsRead($userId)
    {
        ChatMessage::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false) // or 0 if using integers
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}