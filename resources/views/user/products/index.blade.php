@extends('layouts.app')

@section('title', 'Sản phẩm - Showroom hiện đại')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero + Stats -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-700">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute -top-16 -right-16 w-80 h-80 rounded-full bg-white"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-white"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="text-white">
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Khám phá xe hơi & phụ kiện</h1>
                    <p class="mt-2 text-indigo-100">Chọn mẫu xe ưng ý và trang bị phụ kiện phù hợp, tất cả trong một nơi.</p>
                </div>
            </div>
            <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-white/10 rounded-xl p-4 text-white">
                    <div class="text-xs uppercase opacity-80">Tổng sản phẩm</div>
                    <div class="text-2xl font-bold">{{ number_format(($stats['total_variants'] ?? 0) + ($stats['acc_total'] ?? 0)) }}</div>
                </div>
                <div class="bg-white/10 rounded-xl p-4 text-white">
                    <div class="text-xs uppercase opacity-80">Mẫu xe</div>
                    <div class="text-2xl font-bold">{{ number_format($stats['total_variants'] ?? 0) }}</div>
                </div>
                <div class="bg-white/10 rounded-xl p-4 text-white">
                    <div class="text-xs uppercase opacity-80">Phụ kiện</div>
                    <div class="text-2xl font-bold">{{ number_format($stats['acc_total'] ?? 0) }}</div>
                </div>
                <div class="bg-white/10 rounded-xl p-4 text-white">
                    <div class="text-xs uppercase opacity-80">Đang có sẵn</div>
                    <div class="text-2xl font-bold">{{ number_format(($stats['in_stock'] ?? 0) + ($stats['acc_in_stock'] ?? 0)) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters Sidebar -->
            <aside class="lg:w-72 w-full">
                <!-- Mobile filter toggle -->
                <details class="lg:hidden bg-white rounded-xl shadow-sm">
                    <summary class="list-none flex items-center justify-between p-4 cursor-pointer">
                        <span class="font-semibold text-gray-900">Bộ lọc</span>
                        <i class="fas fa-sliders-h text-gray-500"></i>
                    </summary>
                    <div class="px-4 pb-4">
                        @include('user.products.partials.filters', ['formId' => 'filter-form-mobile'])
                        </div>
                </details>

                <!-- Desktop filters -->
                <div class="hidden lg:block bg-white rounded-xl shadow-sm p-5 sticky top-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-900">Bộ lọc</h3>
                        <button type="button" class="js-clear-filters text-sm text-indigo-600 hover:underline">Xóa</button>
                        </div>
                    @include('user.products.partials.filters', ['formId' => 'filter-form'])
                            </div>
            </aside>

            <!-- Result + Controls -->
            <section class="flex-1 min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div id="inv-count" class="text-sm text-gray-600">
                        @if(isset($mode) && $mode === 'accessory' && isset($accessories))
                            {{ number_format($accessories->total()) }} kết quả (Phụ kiện)
                        @elseif(isset($mode) && $mode === 'variants' && isset($variants))
                            {{ number_format($variants->total()) }} kết quả (Xe hơi)
                        @else
                            {{ number_format(($stats['total_variants'] ?? 0) + ($stats['acc_total'] ?? 0)) }} kết quả (Tất cả)
                        @endif
                        </div>
                    <form id="sort-form" method="GET" action="{{ route($routeName ?? 'products.index') }}" class="inventory-filter-form flex items-center gap-2">
                        @foreach(request()->except(['sort','order','q','page','cars_page','acc_page']) as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                @endforeach
                        <div class="flex items-center gap-2 flex-1 min-w-[220px] sm:min-w-[260px] max-w-xl">
                            <div class="relative flex-1">
                                <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm xe, hãng, model..." aria-label="Tìm kiếm"
                                       class="search-input w-full rounded-lg border border-gray-200 pl-10 pr-12 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" autocomplete="off" />
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-2 py-1 text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <label class="text-sm text-gray-600">Sắp xếp</label>
                        <select name="sort" class="text-sm border-gray-200 rounded-lg">
                            @php($t = request('type','all'))
                            @if($t === 'accessory')
                                <option value="name" {{ request('sort','name')==='name' ? 'selected' : '' }}>Tên phụ kiện</option>
                                <option value="price" {{ request('sort')==='price' ? 'selected' : '' }}>Giá</option>
                            @elseif($t === 'car')
                                <option value="name" {{ request('sort','name')==='name' ? 'selected' : '' }}>Tên xe</option>
                                <option value="price" {{ request('sort')==='price' ? 'selected' : '' }}>Giá</option>
                            @else
                                <option value="name" {{ request('sort','name')==='name' ? 'selected' : '' }}>Tên</option>
                                <option value="price" {{ request('sort')==='price' ? 'selected' : '' }}>Giá</option>
                            @endif
                        </select>
                        <select name="order" class="text-sm border-gray-200 rounded-lg">
                            <option value="asc" {{ request('order','asc')==='asc' ? 'selected' : '' }}>Tăng dần</option>
                            <option value="desc" {{ request('order')==='desc' ? 'selected' : '' }}>Giảm dần</option>
                            </select>
                    </form>
            </div>

                <div id="inv-results">
                @if(isset($mode) && $mode === 'accessory')
                    @if($accessories->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($accessories as $accessory)
                                @include('components.accessory-card', ['accessory' => $accessory])
                            @endforeach
                        </div>
                        <div class="mt-8">
                            @include('components.pagination-modern', ['paginator' => $accessories->withQueryString()])
                        </div>
                    @else
                        <div class="bg-white rounded-xl p-10 text-center text-gray-600 shadow-sm">
                            <i class="fas fa-search text-3xl text-gray-300"></i>
                            <div class="mt-3 font-semibold text-gray-900">Không tìm thấy phụ kiện phù hợp</div>
                            <div class="text-sm">Hãy thử thay đổi điều kiện lọc hoặc từ khóa tìm kiếm.</div>
                        </div>
                    @endif
                @elseif(isset($mode) && $mode === 'variants')
                    @if($variants->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($variants as $variant)
                                @include('components.variant-card', ['variant' => $variant, 'showCompare' => true])
                            @endforeach
                        </div>
                        <div class="mt-8">
                            @include('components.pagination-modern', ['paginator' => $variants->withQueryString()])
                        </div>
                    @else
                        <div class="bg-white rounded-xl p-10 text-center text-gray-600 shadow-sm">
                            <i class="fas fa-search text-3xl text-gray-300"></i>
                            <div class="mt-3 font-semibold text-gray-900">Không tìm thấy xe phù hợp</div>
                            <div class="text-sm">Hãy thử thay đổi điều kiện lọc hoặc từ khóa tìm kiếm.</div>
                        </div>
                    @endif
                @else
                    @php($hasCars = isset($variants) && $variants->count() > 0)
                    @php($hasAcc = isset($accessories) && $accessories->count() > 0)
                    @if(!$hasCars && !$hasAcc)
                        <div class="bg-white rounded-xl p-10 text-center text-gray-600 shadow-sm">
                            <i class="fas fa-search text-3xl text-gray-300"></i>
                            <div class="mt-3 font-semibold text-gray-900">Không tìm thấy sản phẩm phù hợp</div>
                            <div class="text-sm">Hãy thử thay đổi điều kiện lọc hoặc từ khóa tìm kiếm.</div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @if($hasCars)
                                @foreach($variants as $variant)
                                    @include('components.variant-card', ['variant' => $variant, 'showCompare' => true])
                                @endforeach
                            @endif
                            @if($hasAcc)
                                @foreach($accessories as $accessory)
                                    @include('components.accessory-card', ['accessory' => $accessory])
                                @endforeach
                            @endif
                        </div>
                        <div class="mt-8">
                            @include('components.pagination-modern', ['paginator' => $variants->withQueryString()])
                        </div>
                    @endif
                @endif
            </div>

            </section>
        </div>
    </div>

    <!-- CTA Section - Full Width -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white rounded-full translate-y-12 -translate-x-12"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10">
            <div class="text-center text-white">
                <div class="mb-8">
                    <i class="fas fa-car text-4xl text-indigo-200 mb-4"></i>
                    <h2 class="text-2xl md:text-3xl font-bold mb-3">Chưa tìm thấy xe ưng ý?</h2>
                    <p class="text-indigo-100 text-lg max-w-2xl mx-auto">
                        Đội ngũ tư vấn chuyên nghiệp của chúng tôi sẵn sàng hỗ trợ bạn tìm kiếm mẫu xe phù hợp nhất với nhu cầu và ngân sách.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                        <i class="fas fa-phone-alt text-2xl text-indigo-200 mb-3"></i>
                        <h3 class="font-semibold mb-2">Tư vấn miễn phí</h3>
                        <p class="text-sm text-indigo-100">Được tư vấn bởi chuyên gia giàu kinh nghiệm</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                        <i class="fas fa-calendar-alt text-2xl text-indigo-200 mb-3"></i>
                        <h3 class="font-semibold mb-2">Lái thử xe</h3>
                        <p class="text-sm text-indigo-100">Đặt lịch lái thử xe tại showroom gần nhất</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                        <i class="fas fa-percentage text-2xl text-indigo-200 mb-3"></i>
                        <h3 class="font-semibold mb-2">Ưu đãi đặc biệt</h3>
                        <p class="text-sm text-indigo-100">Nhận thông tin khuyến mãi mới nhất</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('contact') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white text-indigo-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-envelope mr-2"></i>
                        Liên hệ tư vấn
                    </a>
                    <a href="{{ route('test_drives.book') }}" 
                       class="inline-flex items-center px-6 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-indigo-700 transition-all duration-200">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Đặt lịch lái thử
                    </a>
                </div>
                
                <div class="mt-6 text-sm text-indigo-200">
                    <p>Hoặc gọi trực tiếp: <span class="font-semibold">1900 1234</span> (Miễn phí)</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Tối ưu line-clamp cho các trình duyệt cũ */
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
/* Mượt hiệu ứng khi thay nội dung kết quả */
@keyframes fadeSlideIn { from { opacity: 0; transform: translateY(6px);} to { opacity: 1; transform: translateY(0);} }
.fade-in { animation: fadeSlideIn .25s ease-out; }
.inv-smooth { transition: opacity .22s ease; will-change: opacity; }
.inv-smooth.dim { opacity: .6; }
.inv-smooth.ready { opacity: 1; }
@media (prefers-reduced-motion: reduce) {
  .fade-in { animation: none; }
  .inv-smooth { transition: none; }
}
/* Filter loading effect */
.filter-loading { position: relative; height: 3px; margin-top: 8px; }
.filter-loading-bar { position: absolute; left: 0; top: 0; height: 100%; width: 30%; background: linear-gradient(90deg, #6366f1, #a78bfa); border-radius: 9999px; animation: slide 1s infinite ease-in-out; box-shadow: 0 0 12px rgba(99,102,241,.35); }
@keyframes slide { 0% { transform: translateX(0); } 50% { transform: translateX(230%); } 100% { transform: translateX(0); } }
</style>
@endpush

@push('scripts')
<script>
// AJAX apply filters without full page reload (mobile, tablet, desktop)
(function(){
  const results = document.getElementById('inv-results');
  const count = document.getElementById('inv-count');
  const forms = Array.from(document.querySelectorAll('.inventory-filter-form, #filter-form, #filter-form-mobile'));
  if (!results || forms.length === 0) return;

  function getActiveForm(){
    return document.querySelector('#filter-form') || document.querySelector('#filter-form-mobile') || document.querySelector('.inventory-filter-form') || forms[0] || null;
  }
  function getCurrentType(){
    const f = getActiveForm();
    if (!f) return 'all';
    const hidden = f.querySelector('input[name="type"]');
    return (hidden && hidden.value) ? hidden.value : 'all';
  }
  function setDisabledWithin(el, disabled){
    if (!el) return;
    el.querySelectorAll('input, select, textarea, button').forEach(node => {
      if (node.name === 'type') return; // keep type
      node.disabled = !!disabled;
    });
  }
  function syncPanelsByType(type){
    const f = getActiveForm();
    if (!f) return;
    const panels = f.querySelectorAll('.filter-panel');
    panels.forEach(p => {
      const target = p.getAttribute('data-panel');
      let show = false;
      if (type === 'car' && target === 'car') show = true;
      if (type === 'accessory' && target === 'accessory') show = true;
      if (type === 'all' && target === 'all') show = true;
      p.style.display = show ? '' : 'none';
      setDisabledWithin(p, !show);
    });
    // Update tab visual state
    f.querySelectorAll('.js-type-tab').forEach(btn => {
      const val = btn.getAttribute('data-value');
      const active = val === type;
      btn.setAttribute('aria-selected', active ? 'true' : 'false');
      btn.classList.toggle('bg-white', active);
      btn.classList.toggle('text-gray-900', active);
      btn.classList.toggle('shadow', active);
      btn.classList.toggle('text-gray-600', !active);
    });
  }
  function updateSortOptionsByType(type, selectedSort){
    const sortForm = document.querySelector('.inventory-filter-form');
    if (!sortForm) return;
    const select = sortForm.querySelector('select[name="sort"]');
    if (!select) return;
    const current = selectedSort || select.value || 'name';
    const makeOption = (value, label) => {
      const opt = document.createElement('option');
      opt.value = value; opt.textContent = label;
      if (value === current) opt.selected = true;
      return opt;
    };
    // Reset options
    while (select.firstChild) select.removeChild(select.firstChild);
    if (type === 'accessory') {
      select.appendChild(makeOption('name', 'Tên phụ kiện'));
      select.appendChild(makeOption('price', 'Giá'));
    } else if (type === 'car') {
      select.appendChild(makeOption('name', 'Tên xe'));
      select.appendChild(makeOption('price', 'Giá'));
    } else {
      select.appendChild(makeOption('name', 'Tên'));
      select.appendChild(makeOption('price', 'Giá'));
    }
  }
  function buildParams(fromForm){
    const f = fromForm || getActiveForm();
    const params = new URLSearchParams(new FormData(f));
    const ct = f.querySelector('input[name="type"]:checked');
    if (ct) {
      params.set('type', ct.value);
    } else {
      const hiddenType = f.querySelector('input[name="type"]');
      if (hiddenType && hiddenType.value) params.set('type', hiddenType.value);
    }
    // Remove empty values and defaults to keep URL clean
    Array.from(params.keys()).forEach((k)=>{
      const v = (params.get(k) ?? '').toString().trim();
      if (v === '') { params.delete(k); return; }
    });
    // Remove default type=all
    if (params.get('type') === 'all') params.delete('type');
    // Remove default sort/order when not needed
    const sort = params.get('sort');
    const order = params.get('order');
    if (!sort || sort === 'name') params.delete('sort');
    if (!order || order === 'asc') params.delete('order');
    return params;
  }
  function spin(){
    // Soften content instead of replacing to avoid layout jank
    results.classList.add('inv-smooth','dim');
    const h = results.offsetHeight;
    results.style.minHeight = h + 'px';
    const f = getActiveForm();
    const bar = f && f.querySelector('.js-filter-loading');
    if (bar) bar.classList.remove('hidden');
  }
  function fetchAndRender(params){
    const f = getActiveForm();
    const url = new URL(f.action, window.location.origin);
    url.search = params.toString();
    spin();
    fetch(url, { headers: { 'X-Requested-With':'XMLHttpRequest', 'Accept': 'text/html' } })
      .then(r => r.text())
      .then(html => {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        const scope = tmp.querySelector('#inv-results') ? tmp : (tmp.querySelector('main') || tmp);
        const newResults = scope.querySelector('#inv-results');
        const newCount = scope.querySelector('#inv-count');
        const newFilter = scope.querySelector('#filter-form');
        if (newResults) {
          results.innerHTML = newResults.innerHTML;
          results.classList.add('fade-in');
          setTimeout(()=> results.classList.remove('fade-in'), 260);
        }
        if (newCount && count) count.innerHTML = newCount.innerHTML;
        const currentFilter = document.querySelector('#filter-form');
        if (newFilter && currentFilter) {
          currentFilter.outerHTML = newFilter.outerHTML;
          // Re-sync panels on new filter markup according to current params
          const t = (params.get('type') || 'all');
          syncPanelsByType(t);
        }
        // Replace top sort form so its options match current type
        const newSortForm = scope.querySelector('#sort-form');
        const currentSortForm = document.querySelector('#sort-form');
        if (newSortForm && currentSortForm) {
          currentSortForm.outerHTML = newSortForm.outerHTML;
        } else {
          // Fallback: adjust options in place
          updateSortOptionsByType((params.get('type') || 'all'), params.get('sort') || 'name');
        }
        const q = params.toString();
        const newHref = q ? (url.pathname + '?' + q) : url.pathname;
        history.replaceState({}, '', newHref);
        // Restore
        requestAnimationFrame(()=>{
          results.classList.remove('dim');
          results.classList.add('ready');
          setTimeout(()=> { results.classList.remove('inv-smooth','ready'); results.style.minHeight=''; }, 240);
          const f2 = getActiveForm();
          const bar2 = f2 && f2.querySelector('.js-filter-loading');
          if (bar2) bar2.classList.add('hidden');
        });
      })
      .catch(()=>{ results.innerHTML = '<div class="bg-white rounded-xl p-10 text-center text-gray-600 shadow-sm">Không thể tải dữ liệu. Vui lòng thử lại.</div>'; });
  }

  // Intercept clicks on pagination and any link within filter form to keep AJAX flow
  document.addEventListener('click', function(e){
    const a = e.target.closest('a');
    if (!a) return;
    // Pagination inside results
    if (a.closest('#inv-results') && a.getAttribute('href') && a.getAttribute('href').includes('page=')){
      e.preventDefault();
      const url = new URL(a.getAttribute('href'), window.location.origin);
      // preserve current filters
      const params = buildParams();
      if (url.search) {
        const clicked = new URLSearchParams(url.search);
        clicked.forEach((v,k)=>params.set(k,v));
      }
      fetchAndRender(params);
      return;
    }
    // Links within filter (e.g., clear, brand shortcuts) should use AJAX
    if (a.closest('#filter-form') || a.closest('#filter-form-mobile')) {
      e.preventDefault();
      const url = new URL(a.getAttribute('href'), window.location.origin);
      const params = new URLSearchParams(url.search);
      fetchAndRender(params);
      return;
    }
  });

  // Prevent native submit (all forms) and trigger AJAX
  document.addEventListener('submit', function(e){
    const f = e.target.closest('.inventory-filter-form, #filter-form, #filter-form-mobile');
    if (!f) return;
    e.preventDefault();
    const params = buildParams(f);
    // ensure only one q param
    if (params.getAll('q').length > 1) {
      const q = params.get('q');
      params.delete('q');
      if (q) params.set('q', q);
    }
    fetchAndRender(params);
  });
  // Change handlers (all forms)
  document.addEventListener('change', function(e){
    const f = e.target.closest('.inventory-filter-form, #filter-form, #filter-form-mobile');
    if (!f) return;
    if (e.target && (e.target.matches('input, select'))){
      fetchAndRender(buildParams(f));
    }
  });
  // Quick price buttons (delegated)
  document.addEventListener('click', function(e){
    const btn = e.target.closest('#filter-form button[name="price_quick"]');
    if (!btn) return;
    e.preventDefault();
    const f = getActiveForm();
    const [min,max] = (btn.value||'').split('-');
    const minInp = f.querySelector('input[name="price_min"]');
    const maxInp = f.querySelector('input[name="price_max"]');
    if (minInp) minInp.value = min || '';
    if (maxInp) maxInp.value = max || '';
    fetchAndRender(buildParams(f));
  });
  // Tabs for type (delegated)
  document.addEventListener('click', function(e){
    const tab = e.target.closest('#filter-form .js-type-tab');
    if (!tab) return;
    e.preventDefault();
    const f = getActiveForm();
    const val = tab.getAttribute('data-value') || 'all';
    const hidden = f.querySelector('input[name="type"]');
    if (hidden) hidden.value = val;
    // Instant panel toggle and disable hidden inputs
    syncPanelsByType(val);
    // Instantly update sort options to reflect selected type
    updateSortOptionsByType(val, (new URLSearchParams(location.search)).get('sort') || 'name');
    // Reset page and unrelated filters
    const params = buildParams(f);
    params.delete('page');
    params.delete('cars_page');
    params.delete('acc_page');
    if (val !== 'accessory') { params.delete('acc_brand'); params.delete('acc_category'); params.delete('stock_status'); }
    if (val === 'accessory') { params.delete('fuel_type'); params.delete('transmission'); params.delete('body_type'); params.delete('brand'); params.delete('model'); }
    Array.from(params.keys()).forEach((k)=>{ const v = (params.get(k) ?? '').toString().trim(); if (v === '') params.delete(k); });
    fetchAndRender(params);
  });

  // Clear filters (stay on current tab, no full reload)
  document.addEventListener('click', function(e){
    const btn = e.target.closest('.js-clear-filters');
    if (!btn) return;
    e.preventDefault();
    const f = getActiveForm();
    if (!f) return;
    const params = new URLSearchParams();
    // keep current tab
    const currentType = getCurrentType();
    if (currentType && currentType !== 'all') params.set('type', currentType);
    // reset page and sorts to defaults
    // nothing else
    fetchAndRender(params);
  });

  // Initial sync on load
  syncPanelsByType(getCurrentType());
  updateSortOptionsByType(getCurrentType(), (new URLSearchParams(location.search)).get('sort') || 'name');
  // Search clear button (delegated)
  document.addEventListener('click', function(e){
    const btn = e.target.closest('.js-search-clear');
    if (!btn) return;
    const form = document.getElementById('sort-form');
    if (!form) return;
    const input = form.querySelector('input[name="q"]');
    if (input) input.value = '';
    const params = buildParams(form);
    params.delete('q');
    fetchAndRender(params);
  });
})();

// Inventory deposit button behavior (legacy - left for UI compatibility)
document.addEventListener('click', function(e){
  const btn = e.target.closest('.js-inv-deposit');
  if (!btn) return;
  const status = (btn.getAttribute('data-status')||'').toLowerCase();
  const depositUrl = btn.getAttribute('data-url');
  if (status === 'available') {
    window.location.href = depositUrl;
  } else if (status === 'in_transit') {
    showToast('Xe đang trên đường về. Vui lòng để lại thông tin để chúng tôi liên hệ khi có xe.', 'info');
  } else if (status === 'reserved') {
    showToast('Xe đã được đặt. Bạn có thể chọn mẫu khác hoặc liên hệ showroom để biết thêm.', 'error');
  } else if (status === 'sold') {
    showToast('Xe đã bán. Vui lòng chọn mẫu xe khác.', 'error');
  } else {
    showToast('Tạm thời không thể đặt cọc cho xe này. Vui lòng thử lại sau.', 'error');
  }
});
</script>
@endpush
@endsection



