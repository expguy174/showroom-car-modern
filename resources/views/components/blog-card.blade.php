@props(['blog'])

<article class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
  <div class="relative overflow-hidden">
    @php $img = $blog->image_url ?? null; @endphp
    @if($img)
      <img src="{{ $img }}" 
           alt="{{ $blog->title }}" 
           class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" decoding="async" width="600" height="300" onerror="this.onerror=null;this.src='https://via.placeholder.com/600x300?text=No+Image';">
    @else
      <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
        <i class="fas fa-newspaper text-gray-400 text-4xl"></i>
      </div>
    @endif
    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-medium text-purple-600">
      {{ $blog->category }}
    </div>
  </div>
  <div class="p-6">
    <div class="flex items-center text-sm text-gray-500 mb-3">
      <i class="fas fa-calendar mr-2"></i>
      <span>{{ $blog->created_at->format('d/m/Y') }}</span>
      <span class="mx-2">•</span>
      <i class="fas fa-user mr-2"></i>
      <span>{{ $blog->author_name }}</span>
    </div>
    <h3 class="font-bold text-lg text-gray-900 mb-3 group-hover:text-purple-600 transition-colors duration-300 line-clamp-2">
      {{ $blog->title }}
    </h3>
    <p class="text-gray-600 mb-4 line-clamp-3">
      {{ $blog->excerpt }}
    </p>
    <a href="{{ route('blogs.show', $blog->id) }}" 
       class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700 transition-colors duration-300">
      <span>Đọc thêm</span>
      <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
    </a>
  </div>
</article>


