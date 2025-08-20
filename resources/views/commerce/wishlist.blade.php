@extends('layouts.base')

@section('title','Wishlist')

@section('content')
<h1 class="text-2xl font-bold mb-4">Wishlist</h1>
@if($items->isEmpty())
    <p class="text-gray-600">Chưa có sản phẩm nào.</p>
@else
    <div class="space-y-3">
        @foreach($items as $line)
            @php $item = $line->item; @endphp
            <div class="bg-white rounded shadow p-4 flex items-center justify-between">
                <div>
                    <div class="font-semibold">
                        {{ $item->model->brand->name }} {{ $item->model->name }} - {{ $item->name }}
                    </div>
                </div>
                <form method="post" action="{{ route('wishlist.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="variant_id" value="{{ $item->id }}" />
                    <button class="px-3 py-1 border rounded">Xoá</button>
                </form>
            </div>
        @endforeach
    </div>
@endif
@endsection


