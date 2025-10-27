<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $query = Blog::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('content', 'like', "%$search%");
            });
        }

        if ($status && $status !== '') {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'featured') {
                $query->where('is_featured', true);
            } elseif ($status === 'normal') {
                $query->where('is_featured', false);
            }
        }

        $blogs = $query->orderBy('created_at', 'desc')->paginate(15);

        // Append query parameters to pagination links
        $blogs->appends($request->except(['page', 'ajax', 'with_stats']));

        // Calculate stats for initial load
        $stats = [
            'total' => Blog::count(),
            'active' => Blog::where('is_active', true)->count(),
            'inactive' => Blog::where('is_active', false)->count(),
            'featured' => Blog::where('is_featured', true)->count(),
        ];

        // Return partial view for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return view('admin.blogs.partials.table', compact('blogs'))->render();
        }
        
        return view('admin.blogs.index', compact('blogs', 'search', 'status', 'stats'));
    }

    public function getStats(Request $request)
    {
        $stats = [
            'total' => Blog::count(),
            'active' => Blog::where('is_active', true)->count(),
            'inactive' => Blog::where('is_active', false)->count(),
            'featured' => Blog::where('is_featured', true)->count(),
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($stats);
        }

        return $stats;
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Handle checkboxes separately
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
            $validated['user_id'] = Auth::id();

            // Upload ảnh nếu có
            if ($request->hasFile('image_path')) {
                $validated['image_path'] = $request->file('image_path')->store('uploads/blogs', 'public');
            }

            Blog::create($validated);

            // Return JSON for AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tạo bài viết thành công!',
                    'redirect' => route('admin.blogs.index')
                ]);
            }

            return redirect()->route('admin.blogs.index')->with('success', 'Tạo bài viết thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại thông tin đã nhập.');
        }
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        try {
            $blog = Blog::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Handle checkboxes separately
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;

            // Nếu có upload ảnh mới thì xoá ảnh cũ rồi lưu ảnh mới
            if ($request->hasFile('image_path')) {
                if ($blog->image_path && Storage::disk('public')->exists($blog->image_path)) {
                    Storage::disk('public')->delete($blog->image_path);
                }
                $validated['image_path'] = $request->file('image_path')->store('uploads/blogs', 'public');
            }

            $blog->update($validated);

            // Return JSON for AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật bài viết thành công!',
                    'redirect' => route('admin.blogs.index')
                ]);
            }

            return redirect()->route('admin.blogs.index')->with('success', 'Cập nhật bài viết thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại thông tin đã nhập.');
        }
    }

    public function toggle(Blog $blog, Request $request)
    {
        $blog->update(['is_active' => !$blog->is_active]);
        
        if ($request->ajax() || $request->wantsJson()) {
            $stats = [
                'total' => Blog::count(),
                'active' => Blog::where('is_active', true)->count(),
                'inactive' => Blog::where('is_active', false)->count(),
                'featured' => Blog::where('is_featured', true)->count(),
            ];
            
            return response()->json([
                'success' => true,
                'message' => $blog->is_active ? 'Đã kích hoạt bài viết!' : 'Đã tạm dừng bài viết!',
                'stats' => $stats
            ]);
        }
        
        return redirect()->back()->with('success', $blog->is_active ? 'Đã kích hoạt bài viết!' : 'Đã tạm dừng bài viết!');
    }

    public function destroy($id)
    {
        try {
            $blog = Blog::findOrFail($id);

            // Check if blog is currently active
            if ($blog->is_active) {
                $message = "Không thể xóa bài viết \"{$blog->title}\" vì đang ở trạng thái HIỂN THỊ. Vui lòng ẩn bài viết trước khi xóa.";
                
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'action_suggestion' => 'deactivate'
                    ], 400);
                }
                return redirect()->route('admin.blogs.index')->with('error', $message);
            }

            // Store info before deletion
            $blogTitle = $blog->title;
            $imagePath = $blog->image_path;

            // Xoá ảnh nếu có
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Delete blog
            $blog->delete();

            // Get updated stats
            $stats = [
                'total' => Blog::count(),
                'active' => Blog::where('is_active', true)->count(),
                'inactive' => Blog::where('is_active', false)->count(),
                'featured' => Blog::where('is_featured', true)->count(),
            ];

            $message = "Đã xóa bài viết \"{$blogTitle}\" thành công!";

            // Return JSON for AJAX requests
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'stats' => $stats
                ]);
            }

            return redirect()->route('admin.blogs.index')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting blog: ' . $e->getMessage());
            
            $message = 'Có lỗi xảy ra khi xóa bài viết!';
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            return redirect()->route('admin.blogs.index')->with('error', $message);
        }
    }
}