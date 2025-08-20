<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('user.blogs.index', compact('blogs'));
    }

    public function show(Blog $blog)
    {
        if (!$blog->is_published) {
            abort(404);
        }

        $recentBlogs = Blog::where('is_published', true)
            ->where('id', '!=', $blog->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('user.blogs.show', compact('blog', 'recentBlogs'));
    }
} 