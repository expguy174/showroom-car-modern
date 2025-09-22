@extends('layouts.admin')

@section('title', 'Cập nhật sản phẩm')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">✏️ CẬP NHẬT SẢN PHẨM</h1>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Tên sản phẩm</label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="w-full border-gray-300 rounded px-4 py-2" required>
        </div>

        <div class="mb-4">
            <label for="product_type" class="block text-sm font-semibold text-gray-700 mb-1">Loại</label>
            <select id="product_type" name="product_type" required class="w-full border-gray-300 rounded px-4 py-2">
                <option value="car" {{ $product->product_type == 'car' ? 'selected' : '' }}>Car</option>
                <option value="accessory" {{ $product->product_type == 'accessory' ? 'selected' : '' }}>Accessory</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-sm font-semibold text-gray-700 mb-1">Giá</label>
            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" class="w-full border-gray-300 rounded px-4 py-2" required>
        </div>

        <div class="mb-4">
            <label for="image_url" class="block text-sm font-semibold text-gray-700 mb-1">Link hình ảnh</label>
            <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $product->image_url) }}" class="w-full border-gray-300 rounded px-4 py-2">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Mô tả</label>
            <textarea id="description" name="description" rows="4" class="w-full border-gray-300 rounded px-4 py-2">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-4 flex items-center gap-3">
            <input type="checkbox" id="is_active" name="is_active" value="1" class="text-indigo-600" {{ $product->is_active ? 'checked' : '' }}>
            <label for="is_active" class="text-sm text-gray-700">Hiển thị</label>
        </div>

        <div class="text-right">
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded shadow hover:bg-indigo-700">
                Cập nhật sản phẩm
            </button>
        </div>
    </form>
</div>
@endsection