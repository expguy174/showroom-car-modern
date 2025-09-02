@extends('layouts.admin')

@section('title', 'Trang quản trị hệ thống')

@section('content')
<div class="bg-white shadow rounded p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">🎯 Admin Dashboard</h1>

    <p class="text-gray-700 text-lg">
        Xin chào, <strong class="text-indigo-600">{{ optional(Auth::user()->userProfile)->name ?? 'Admin' }}</strong> 👋
    </p>

    <p class="text-gray-600 mt-2">
        Bạn đang đăng nhập với quyền <span class="bg-gray-200 text-gray-800 px-2 py-0.5 rounded text-sm">admin</span>. <br>
        Đây là trang quản trị hệ thống showroom, nơi bạn có thể quản lý toàn bộ dữ liệu.
    </p>
</div>
@endsection