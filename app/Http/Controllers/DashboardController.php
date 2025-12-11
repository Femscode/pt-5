<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Connection;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    
    public function index() {
        $user = Auth::user();
        $events = Event::latest()->get();
        $received = Connection::pending()->where('receiver_id', $user->id)->with('sender')->get();
        $sent = Connection::pending()->where('sender_id', $user->id)->with('receiver')->get();
        return view('dashboard.index', compact('user','events','received','sent'));
    }
    public function network() {
        $user = Auth::user();
        $allUsers = User::where('id', '!=', $user->id)->orderBy('full_name')->get();

        $received = Connection::pending()->where('receiver_id', $user->id)->with('sender')->get();
        $sent = Connection::pending()->where('sender_id', $user->id)->with('receiver')->get();
        $accepted = Connection::accepted()
            ->where(function($q) use ($user) {
                $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->with(['sender','receiver'])
            ->get();

        $counts = [
            'connections' => $accepted->count(),
            'pending_invites' => $received->count(),
            'sent_requests' => $sent->count(),
        ];

        return view('dashboard.network', compact('user','allUsers','received','sent','accepted','counts'));
    }

    public function event()
    {
        $user = Auth::user();
        $events = Event::orderBy('start_date', 'desc')->get();
        $subscribedIds = \App\Models\EventSubscription::where('user_id', $user->id)->pluck('event_id')->all();
        return view('dashboard.event', compact('user', 'events','subscribedIds'));
    }

    public function eventShow(Event $event)
    {
        $user = Auth::user();
        $isSubscribed = \App\Models\EventSubscription::where('event_id',$event->id)->where('user_id',$user->id)->exists();
        return view('dashboard.event_show', compact('user','event','isSubscribed'));
    }

    public function subscribe(Request $request, Event $event)
    {
        $user = $request->user();
        $existing = \App\Models\EventSubscription::where('event_id', $event->id)->where('user_id', $user->id)->first();
        if ($existing) {
            return response()->json(['ok' => true, 'message' => 'Already registered', 'subscriptionId' => $existing->id]);
        }
        $sub = \App\Models\EventSubscription::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'phone' => $user->phone ?? null,
        ]);
        return response()->json(['ok' => true, 'message' => 'Registered successfully', 'subscriptionId' => $sub->id]);
    }

    public function sendConnection(Request $request)
    {
        $request->validate(['receiver_id' => 'required|integer']);
        $senderId = Auth::id();
        $receiverId = (int) $request->input('receiver_id');
        if ($receiverId === $senderId) {
            return response()->json(['ok' => false, 'message' => 'You cannot connect to yourself'], 422);
        }
        $existing = Connection::where(function($q) use ($senderId, $receiverId) {
            $q->where('sender_id', $senderId)->where('receiver_id', $receiverId);
        })->orWhere(function($q) use ($senderId, $receiverId) {
            $q->where('sender_id', $receiverId)->where('receiver_id', $senderId);
        })->first();

        if ($existing) {
            if ($existing->status === 'accepted') {
                return response()->json(['ok' => true, 'message' => 'Already connected']);
            }
            if ($existing->status === 'pending') {
                return response()->json(['ok' => true, 'message' => 'Request already pending']);
            }
            $existing->status = 'pending';
            $existing->sender_id = $senderId;
            $existing->receiver_id = $receiverId;
            $existing->save();
            return response()->json(['ok' => true, 'message' => 'Request re-sent']);
        }

        Connection::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'status' => 'pending',
        ]);

        return response()->json(['ok' => true, 'message' => 'Connection request sent']);
    }

    public function acceptConnection(Request $request)
    {
        $request->validate(['connection_id' => 'required|integer']);
        $conn = Connection::where('id', $request->input('connection_id'))
            ->where('receiver_id', Auth::id())
            ->first();
        if (!$conn) return response()->json(['ok' => false, 'message' => 'Request not found'], 404);
        $conn->status = 'accepted';
        $conn->save();
        return response()->json(['ok' => true, 'message' => 'Request accepted']);
    }

    public function rejectConnection(Request $request)
    {
        $request->validate(['connection_id' => 'required|integer']);
        $conn = Connection::where('id', $request->input('connection_id'))
            ->where('receiver_id', Auth::id())
            ->first();
        if (!$conn) return response()->json(['ok' => false, 'message' => 'Request not found'], 404);
        $conn->status = 'rejected';
        $conn->save();
        return response()->json(['ok' => true, 'message' => 'Request declined']);
    }

    public function cancelConnection(Request $request)
    {
        $request->validate(['connection_id' => 'required|integer']);
        $conn = Connection::where('id', $request->input('connection_id'))
            ->where('sender_id', Auth::id())
            ->first();
        if (!$conn) return response()->json(['ok' => false, 'message' => 'Request not found'], 404);
        $conn->status = 'rejected';
        $conn->save();
        return response()->json(['ok' => true, 'message' => 'Request cancelled']);
    }

    public function marketplace()
    {
        $user = Auth::user();
        $products = Product::with('images')->orderBy('created_at', 'desc')->get();
        return view('dashboard.marketplace', compact('user', 'products'));
    }

    public function productShow(Product $product)
    {
        $user = Auth::user();
        $product->load('images');
        $imgUrls = $product->images->sortBy('sort_order')->pluck('image_url')->all();
        $photoArray = is_array($product->photos) ? $product->photos : [];
        $gallery = array_values(array_unique(array_filter(array_merge($imgUrls, $photoArray))));
        return view('dashboard.product_show', compact('user', 'product', 'gallery'));
    }

    public function settings()
    {
        $user = Auth::user();
        return view('dashboard.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = $request->user();
        if ($request->hasFile('image')) {
            $request->validate(['image' => ['image','max:4096']]);
            $file = $request->file('image');
            $dir = public_path('assets/uploads/users');
            if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
            $name = 'u'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->move($dir, $name);
            $relative = 'assets/uploads/users/'.$name;
            $user->image = url($relative);
            $user->save();
            if ($request->expectsJson()) {
                return response()->json(['ok' => true, 'message' => 'Image updated', 'image' => $user->image]);
            }
            return redirect()->route('settings')->with('status', 'Image updated');
        }

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
        ]);
        $user->full_name = $data['full_name'];
        $user->phone = $data['phone'] ?? null;
        $user->save();
        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => 'Profile updated', 'user' => [
                'full_name' => $user->full_name,
                'phone' => $user->phone,
                'email' => $user->email,
                'image' => $user->image,
            ]]);
        }
        return redirect()->route('settings')->with('status', 'Profile updated');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);
        if (!Hash::check($data['current_password'], $user->password)) {
            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'message' => 'Current password is incorrect'], 422);
            }
            return back()->withErrors(['current_password' => 'Current password is incorrect'])->withInput();
        }
        $user->password = Hash::make($data['password']);
        $user->save();
        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => 'Password updated']);
        }
        return redirect()->route('settings')->with('status', 'Password updated');
    }
}
