<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Return partial view for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return view('admin.contact-messages.partials.table', compact('messages'))->render();
        }
        
        return view('admin.contact-messages.index', compact('messages'));
    }

    public function getStats(Request $request)
    {
        $stats = [
            'total' => ContactMessage::count(),
            'new' => ContactMessage::where('status', 'new')->count(),
            'in_progress' => ContactMessage::where('status', 'in_progress')->count(),
            'resolved' => ContactMessage::where('status', 'resolved')->count(),
            'closed' => ContactMessage::where('status', 'closed')->count(),
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($stats);
        }

        return $stats;
    }

    public function show(ContactMessage $contactMessage)
    {
        // Load relationships
        $contactMessage->load(['user.userProfile', 'showroom', 'handledBy']);
        
        // Mark as in_progress when viewing if status is new
        if ($contactMessage->status === 'new') {
            $contactMessage->update(['status' => 'in_progress']);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function markAsRead(ContactMessage $contactMessage, Request $request)
    {
        // Change status from new to in_progress
        if ($contactMessage->status === 'new') {
            $contactMessage->update(['status' => 'in_progress']);
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            // Get updated stats
            $stats = [
                'total' => ContactMessage::count(),
                'new' => ContactMessage::where('status', 'new')->count(),
                'in_progress' => ContactMessage::where('status', 'in_progress')->count(),
                'resolved' => ContactMessage::where('status', 'resolved')->count(),
                'closed' => ContactMessage::where('status', 'closed')->count(),
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Đã chuyển sang đang xử lý!',
                'stats' => $stats
            ]);
        }
        
        return redirect()->back()->with('success', 'Đã chuyển sang đang xử lý!');
    }

    public function updateStatus(ContactMessage $contactMessage, Request $request)
    {
        $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed'
        ]);
        
        $contactMessage->update([
            'status' => $request->status,
            'handled_by' => auth()->guard()->id(),
            'handled_at' => now()
        ]);
        
        if ($request->ajax() || $request->wantsJson()) {
            // Get updated stats
            $stats = [
                'total' => ContactMessage::count(),
                'new' => ContactMessage::where('status', 'new')->count(),
                'in_progress' => ContactMessage::where('status', 'in_progress')->count(),
                'resolved' => ContactMessage::where('status', 'resolved')->count(),
                'closed' => ContactMessage::where('status', 'closed')->count(),
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật trạng thái thành công!',
                'stats' => $stats
            ]);
        }
        
        return redirect()->back()->with('success', 'Đã cập nhật trạng thái thành công!');
    }

    public function destroy(ContactMessage $contactMessage, Request $request)
    {
        $contactMessage->delete();
        
        if ($request->ajax() || $request->wantsJson()) {
            // Get updated stats
            $stats = [
                'total' => ContactMessage::count(),
                'new' => ContactMessage::where('status', 'new')->count(),
                'in_progress' => ContactMessage::where('status', 'in_progress')->count(),
                'resolved' => ContactMessage::where('status', 'resolved')->count(),
                'closed' => ContactMessage::where('status', 'closed')->count(),
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa tin nhắn thành công!',
                'stats' => $stats
            ]);
        }
        
        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Đã xóa tin nhắn thành công!');
    }
}
