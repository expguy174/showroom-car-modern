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
            $query->where('is_read', $request->status === 'read');
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Return partial view for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return view('admin.contact-messages.partials.table', compact('messages'))->render();
        }
        
        return view('admin.contact-messages.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        // Mark as read when viewing
        if (!$contactMessage->is_read) {
            $contactMessage->update(['is_read' => true]);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function markAsRead(ContactMessage $contactMessage, Request $request)
    {
        $contactMessage->update(['is_read' => true]);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã đánh dấu đã đọc!'
            ]);
        }
        
        return redirect()->back()->with('success', 'Đã đánh dấu đã đọc!');
    }

    public function destroy(ContactMessage $contactMessage, Request $request)
    {
        $contactMessage->delete();
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa tin nhắn thành công!'
            ]);
        }
        
        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Đã xóa tin nhắn thành công!');
    }
}
