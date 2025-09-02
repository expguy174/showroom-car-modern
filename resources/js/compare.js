// compare.js - Simple, modern compare controller
// Single source of truth; no jQuery; works on all pages

(function(){
  const KEY = 'compare.variants';
  const MAX = 4;
  const SELECTOR = '.js-compare, .js-compare-toggle';

  function read(){
    try{
      const arr = JSON.parse(localStorage.getItem(KEY) || '[]');
      return Array.isArray(arr) ? arr.map(v=>parseInt(v,10)).filter(v=>!isNaN(v)) : [];
    }catch{ return []; }
  }
  function write(list){
    try{
      localStorage.setItem(KEY, JSON.stringify(list));
      document.cookie = `compare_variants=${encodeURIComponent(JSON.stringify(list))}; path=/; max-age=${60*60*24*7}`;
    }catch{}
  }
  function getId(el){
    if (!el) return null;
    const keys = ['data-variant-id','data-car-id','data-item-id','data-id'];
    for (const k of keys){ const v = el.getAttribute(k); if (v){ const n = parseInt(v,10); if (!isNaN(n)) return n; } }
    return null;
  }
  function setBtnState(btn, active){
    if (!btn) return;
    btn.classList.toggle('border-indigo-300', !!active);
    btn.classList.toggle('border-gray-200', !active);
    const ic = btn.querySelector('i');
    if (ic){ ic.classList.toggle('text-indigo-600', !!active); ic.classList.toggle('text-gray-700', !active); }
  }
  function refreshButtons(){
    const list = read();
    document.querySelectorAll(SELECTOR).forEach(btn => {
      if (btn.hasAttribute('data-accessory-id')) return; // not variant
      const id = getId(btn);
      setBtnState(btn, list.includes(id));
    });
  }
  function updateFab(){
    const list = read();
    const fab = document.getElementById('compare-fab');
    const count = document.getElementById('compare-fab-count');
    if (fab && count){
      count.textContent = String(list.length);
      fab.classList.toggle('hidden', list.length === 0);
    }
  }
  function add(id){
    let list = read();
    if (list.includes(id)) return false;
    if (list.length >= MAX){ if (window.showMessage) window.showMessage(`Chỉ so sánh tối đa ${MAX} mẫu`, 'warning'); return null; }
    list.push(id); write(list);
    try { if (typeof cacheVariantMetaFromDOM === 'function') cacheVariantMetaFromDOM(id); } catch(_){}
    return true;
  }
  function remove(id){
    let list = read().filter(x => x !== id);
    write(list);
    try { if (typeof removeVariantMeta === 'function') removeVariantMeta(id); } catch(_){}
    return true;
  }

  // Fallback toast function
  function showToast(message, type = 'info') {
    if (window.showMessage) {
      window.showMessage(message, type);
      return;
    }
    
    // Add CSS if not already added
    if (!document.getElementById('compare-toast-styles')) {
      const style = document.createElement('style');
      style.id = 'compare-toast-styles';
      style.textContent = `
        .toast-notification {
          position: fixed;
          top: 1rem;
          right: 1rem;
          z-index: 9999;
          padding: 0.75rem 1rem;
          border-radius: 0.5rem;
          box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
          transform: translateX(100%);
          transition: transform 0.3s ease-in-out;
          max-width: 400px;
          min-width: 200px;
          width: auto;
        }
        .toast-notification .toast-content {
          display: flex;
          align-items: center;
          width: 100%;
        }
        .toast-notification .toast-message {
          font-size: 0.875rem;
          font-weight: 500;
          flex: 1;
          word-wrap: break-word;
          overflow-wrap: break-word;
        }
        .toast-notification .toast-close {
          margin-left: 1rem;
          color: rgba(255, 255, 255, 0.9);
          cursor: pointer;
          transition: color 0.2s ease-in-out;
          flex-shrink: 0;
        }
        .toast-notification .toast-close:hover {
          color: white;
        }
        .toast-notification.show {
          transform: translateX(0);
        }
        .toast-notification.hide {
          transform: translateX(100%);
        }
        @media (max-width: 640px) {
          .toast-notification {
            right: 0.5rem;
            left: 0.5rem;
            max-width: none;
            min-width: auto;
            width: auto;
          }
        }
      `;
      document.head.appendChild(style);
    }
    
    // Create fallback toast
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => toast.remove());

    const toast = document.createElement('div');
    toast.className = `toast-notification`;

    const colors = {
      success: 'bg-green-500 text-white',
      error: 'bg-red-500 text-white',
      warning: 'bg-yellow-500 text-white',
      info: 'bg-blue-500 text-white'
    };

    toast.className += ` ${colors[type] || colors.info}`;
    toast.innerHTML = `
      <div class="toast-content">
        <span class="toast-message">${message}</span>
        <button class="toast-close" aria-label="Đóng" onclick="this.closest('.toast-notification').remove()">
          <i class="fas fa-times"></i>
        </button>
      </div>
    `;

    document.body.appendChild(toast);

    requestAnimationFrame(() => {
      toast.classList.add('show');
    });

    setTimeout(() => {
      toast.classList.add('hide');
      setTimeout(() => {
        if (toast.parentElement) toast.remove();
      }, 300);
    }, 3000);
  }

  // Public API
  window.compare = {
    add(id){ const r = add(id); if (r===true){ 
      showToast('Đã thêm vào so sánh', 'success');
      refreshButtons(); updateFab(); 
    } else if (r===false){ 
      showToast('Xe đã có trong so sánh', 'info');
    } },
    remove(id){ if (remove(id)){ 
      showToast('Đã xóa khỏi so sánh', 'info');
      refreshButtons(); updateFab(); 
    } },
    list(){ return read(); },
    clear(){ write([]); refreshButtons(); updateFab(); },
    open(){ const fab = document.getElementById('compare-fab'); if (fab) fab.click(); }
  };

  // Delegated click handler
  document.addEventListener('click', function(e){
    const btn = e.target.closest(SELECTOR);
    if (!btn || btn.hasAttribute('data-accessory-id')) return;
    e.preventDefault();
    const id = getId(btn);
    if (!id) return;
    const list = read();
    if (list.includes(id)) { 
      remove(id); 
      showToast('Đã xóa khỏi so sánh', 'info');
    }
    else { 
      const ok = add(id); 
      if (ok) {
        showToast('Đã thêm vào so sánh', 'success');
      }
    }
    refreshButtons(); updateFab();
  });

  document.addEventListener('DOMContentLoaded', function(){ refreshButtons(); updateFab(); });

  // Open modal from FAB and render table/grid with full info
  document.addEventListener('click', function(e){
    const fab = e.target.closest('#compare-fab');
    if (!fab) return;
    e.preventDefault();
    const modal = document.getElementById('compare-modal');
    const body = document.getElementById('compare-modal-body');
    if (!modal || !body) return;
    modal.classList.remove('hidden');
    // Ensure centered using flex
    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';

    renderCompareModal();
  });

  // Smooth re-render of compare modal without closing
  function renderCompareModal(){
    const modal = document.getElementById('compare-modal');
    const body = document.getElementById('compare-modal-body');
    if (!modal || !body) return;
    body.innerHTML = '<div class="flex items-center justify-center text-gray-500 p-6" style="min-height:220px"><i class="fas fa-spinner fa-spin"></i><span class="ml-2">Đang tải...</span></div>';

    const ids = read();
    if (!ids.length){
      body.innerHTML = '<div class="flex items-center justify-center text-gray-500 py-10" style="min-height:220px"><div class="text-center"><i class="fas fa-balance-scale text-2xl mb-2"></i><div>Chưa có mẫu nào trong danh sách so sánh</div></div></div>';
      return;
    }

    // helpers used in render
    const imgOf = (v) => (v?.image_url) || ((Array.isArray(v?.images)&&v.images[0]&&(v.images[0].image_url||v.images[0].url))) || 'https://via.placeholder.com/300x200/eeeeee/999999?text=Khong+co+anh';
    const val = (x)=>{ const s = String(x ?? '').trim(); if (!s || /^(undefined|null|n\/a|na)$/i.test(s)) return ''; return s; };
    const getSpec = (v, names) => {
      const specs = Array.isArray(v.specifications) ? v.specifications : [];
      const found = specs.find(s => names.includes((s.spec_name||'').toLowerCase()));
      return found ? (found.spec_value + (found.unit ? ` ${found.unit}` : '')) : '';
    };
    const priceInfo = (v)=>{ const raw = Number(v?.current_price ?? v?.final_price ?? v?.price ?? 0) || 0; const base = Number(v?.base_price ?? v?.original_price ?? raw) || 0; return { raw, base }; };
    const fuelVI = (s) => { const map = { gasoline:'Xăng', petrol:'Xăng', gas:'Xăng', diesel:'Dầu', hybrid:'Hybrid', electric:'Điện', ev:'Điện', 'plug-in_hybrid':'Hybrid sạc ngoài', phev:'Hybrid sạc ngoài', hydrogen:'Hydro' }; return map[(String(s||'').toLowerCase())] || s || ''; };
    const transVI = (raw) => { const lower = String(raw||'').toLowerCase(); if(/\bdct\b|dual clutch/.test(lower)) return 'Ly hợp kép'; if(/\becvt\b|\be-cvt\b|\bcvt\b|continuously variable/.test(lower)) return 'CVT'; if(/\bamt\b/.test(lower)) return 'Tự động kiểu cơ'; if(/\bautomatic\b|\bat\b|\ba\/t\b|tiptronic|steptronic/.test(lower)) return 'Tự động'; if(/\bmanual\b|\bmt\b|\bm\/t\b/.test(lower)) return 'Số sàn'; if(/semi[-\s]?automatic/.test(lower)) return 'Bán tự động'; if(/sequential/.test(lower)) return 'Tuần tự'; return raw || ''; };
    const driveVI = (raw) => { const l = String(raw||'').toLowerCase(); if(/awd|4wd|4x4|all-?wheel/.test(l)) return 'Hai cầu'; if(/rwd|rear/.test(l)) return 'Cầu sau'; if(/fwd|front/.test(l)) return 'Cầu trước'; return raw || ''; };

    Promise.all(ids.map(id => fetch(`/api/v1/variants/${id}`, { headers: { 'X-Requested-With':'XMLHttpRequest' } }).then(r=>r.ok?r.json():null).catch(()=>null)))
      .then(res => {
        const items = (res||[]).map(r => (r && r.success && r.data) ? r.data : null).filter(Boolean);
        if (!items.length){
          body.innerHTML = '<div class="text-center text-gray-500 py-10">Không tải được dữ liệu so sánh</div>';
          return;
        }

        // Header cells with image and remove button
        const viewportW = (typeof window !== 'undefined') ? window.innerWidth : 1024;
        const imgHeight = (() => { const base = (items.length <= 2) ? 140 : (items.length === 3 ? 120 : 110); return (viewportW < 640) ? Math.max(96, base - 20) : base; })();
        const headers = items.map(v => {
          const title = `${v?.car_model?.car_brand?.name || ''} ${v?.car_model?.name || ''} – ${v?.name || ''}`.trim();
          return `<th class=\"p-3 align-top bg-gray-50 overflow-visible\">\n            <div class=\"space-y-2\">\n              <div class=\\"relative inline-block overflow-visible\\">\n                <img src=\"${imgOf(v)}\" class=\"w-full object-cover rounded-lg border\" style=\\"height:${imgHeight}px; max-width:240px\\"/>\n                <button data-compare-remove=\"${v.id}\" title=\"Xóa khỏi so sánh\" class=\\"absolute top-1 right-1 z-20 w-6 h-6 md:w-7 md:h-7 inline-flex items-center justify-center rounded-full bg-white/95 border border-red-200 text-red-600 hover:bg-red-600 hover:text-white shadow\\">&times;</button>\n              </div>\n              <div class=\"font-semibold text-gray-900 line-clamp-2\" title=\"${title}\">${title}</div>\n            </div>\n          </th>`;
        }).join('');

        const rows = [];
        const push = (label, arr) => { if (arr.some(x=>x!=='')) rows.push([label, arr]); };
        push('Hãng', items.map(v => val(v?.car_model?.car_brand?.name)));
        push('Dòng xe', items.map(v => val(v?.car_model?.name)));
        push('Phiên bản', items.map(v => val(v?.name)));
        push('Giá', items.map(v => { const { raw, base } = priceInfo(v); const price = raw.toLocaleString('vi-VN')+'₫'; let badge=''; if (base>raw){ const pct=Math.round(((base-raw)/base)*100); badge = `<span class=\"ml-2 inline-flex items-center px-1.5 py-0.5 rounded bg-red-50 text-red-600 text-[11px]\">-${pct}%</span>`;} return `${price}${badge}`; }));
        push('Nhiên liệu', items.map(v => val(fuelVI(getSpec(v, ['fuel_type'])))));
        push('Hộp số', items.map(v => val(transVI(getSpec(v, ['transmission'])))));
        push('Dẫn động', items.map(v => val(driveVI(getSpec(v, ['drivetrain'])))));
        push('Số chỗ', items.map(v => val(getSpec(v, ['seating_capacity']))));
        push('Công suất', items.map(v => val(getSpec(v, ['power_output']))));
        push('Mô-men xoắn', items.map(v => val(getSpec(v, ['torque']))));
        push('Kích thước (D × R × C)', items.map(v => { const L = val(getSpec(v, ['length','dài'])); const W = val(getSpec(v, ['width','rộng'])); const H = val(getSpec(v, ['height','cao'])); const txt=[L,W,H].filter(Boolean).join(' × '); return `<span style=\"white-space:nowrap\">${txt}</span>`; }));
        push('Màu sắc', items.map(v => { const colors = Array.isArray(v?.colors)?v.colors:[]; if (!colors.length) return ''; const names = colors.map(c => (c.color_name||c.name||'').toString().replace(/_/g,' ').trim()).filter(Boolean); return names.join(' - ');}));
        const feats = (v)=>{ const arr = Array.isArray(v?.features_relation)?v.features_relation:(Array.isArray(v?.featuresRelation)?v.featuresRelation:[]); const names = arr.map(f=>f?.feature_name||f?.name).filter(Boolean).slice(0,5).map(s=>String(s).replace(/_/g,' ').trim()); if(!names.length) return ''; return names.join(' - '); };
        push('Tính năng nổi bật', items.map(v => val(feats(v))));
        const tbody = rows.map(([label, arr], idx) => { const norm = arr.map(v=>String(v||'').replace(/<[^>]*>/g,'').trim().toLowerCase()); const allSame = norm.length <= 1 ? true : norm.every(v=>v===norm[0]); const tds = arr.map(v=>`<td class=\"p-3 align-top ${(!allSame ? 'text-indigo-700 font-semibold bg-indigo-50/40 rounded' : '')}\">${v||'-'}</td>`).join(''); const zebra = (idx % 2 === 0) ? 'bg-white' : 'bg-gray-50/40'; const labelClass = 'p-3 font-medium bg-gray-50 whitespace-nowrap'; return `<tr class=\"border-t ${zebra}\"><td class=\"${labelClass}\">${label}</td>${tds}</tr>`; }).join('');

        const prices = items.map(v => priceInfo(v).raw).filter(n=>!isNaN(n));
        const min = prices.length ? Math.min(...prices) : null;
        const max = prices.length ? Math.max(...prices) : null;
        const diff = (min!==null && max!==null) ? (max-min) : null;
        const summary = (items.length>=2) ? `
          <div class=\"flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mt-4 p-3 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-100\">\n            <div class=\"text-xs sm:text-sm text-gray-700 space-x-4\">\n              ${min!==null ? `<span>Thấp nhất: <strong class=\\"text-emerald-600\\">${min.toLocaleString('vi-VN')}₫</strong></span>` : ''}\n              ${max!==null ? `<span>Cao nhất: <strong class=\\"text-rose-600\\">${max.toLocaleString('vi-VN')}₫</strong></span>` : ''}\n              ${diff!==null ? `<span>Chênh lệch: <strong class=\\"text-indigo-600\\">${diff.toLocaleString('vi-VN')}₫</strong></span>` : ''}\n            </div>\n          </div>` : '';

        body.innerHTML = `
          <div class=\"overflow-x-auto rounded-b-2xl\">\n            <table class=\"table-auto w-auto mx-auto inline-block text-[10px] sm:text-[11px] md:text-xs lg:text-sm\">\n              <thead class=\"bg-gray-50\"><tr><th class=\"p-3 text-left text-gray-500 bg-gray-50 whitespace-nowrap\">Thông số</th>${headers}</tr></thead>\n              <tbody>${tbody}</tbody>\n            </table>\n          </div>\n          ${summary}
        `;
      }).catch(() => {
        body.innerHTML = '<div class="text-center text-gray-500 py-10">Không tải được dữ liệu so sánh</div>';
      });
  }

  // Optimistic re-render while fetching fresh data
  function renderCompareModalOptimistic(){
    const modal = document.getElementById('compare-modal');
    const body = document.getElementById('compare-modal-body');
    if (!modal || !body) return;
    const ids = read();
    if (ids.length === 0){
      body.innerHTML = '<div class="flex items-center justify-center text-gray-500 py-10" style="min-height:220px"><div class="text-center"><i class="fas fa-balance-scale text-2xl mb-2"></i><div>Chưa có mẫu nào trong danh sách so sánh</div></div></div>';
      return;
    }
    // Skeleton with the same column count
    const columns = ids.map(() => `<th class=\"p-3 align-top bg-gray-50\"><div class=\"space-y-2\"><div class=\"w-[200px] h-[110px] bg-gray-100 rounded-lg\"></div><div class=\"h-3 bg-gray-100 rounded w-[180px]\"></div></div></th>`).join('');
    body.innerHTML = `
      <div class=\"overflow-x-auto rounded-b-2xl\">\n        <table class=\"table-auto w-auto mx-auto inline-block text-[10px] sm:text-[11px] md:text-xs lg:text-sm\">\n          <thead class=\"bg-gray-50\"><tr><th class=\"p-3 text-left text-gray-500 bg-gray-50 whitespace-nowrap\">Thông số</th>${columns}</tr></thead>\n          <tbody>\n            <tr><td class=\"p-3 bg-gray-50\">Đang cập nhật...</td>${ids.map(()=>'<td class=\\"p-3\\"><div class=\\"h-3 bg-gray-100 rounded w-[120px]\\"></div></td>').join('')}</tr>\n          </tbody>\n        </table>\n      </div>`;
    // Then fetch and render real data
    renderCompareModal();
  }

  // Close, backdrop, ESC
  document.addEventListener('click', function(e){
    if (e.target.closest('#compare-modal-close')){
      e.preventDefault();
      const modal = document.getElementById('compare-modal');
      if (modal) modal.classList.add('hidden');
      return;
    }
    const rm = e.target.closest('[data-compare-remove]');
    if (rm){
      const id = parseInt(rm.getAttribute('data-compare-remove'),10);
      if (id){ remove(id); updateFab(); }
      // optimistic re-render immediately, then full render
      renderCompareModalOptimistic();
      return;
    }
    if (e.target && (e.target.id === 'compare-clear-all' || e.target.id === 'compare-clear-all-top')){
      e.preventDefault();
      write([]); refreshButtons(); updateFab();
      const modal = document.getElementById('compare-modal');
      const body = document.getElementById('compare-modal-body');
      if (body) body.innerHTML = '<div class="flex items-center justify-center text-gray-500 py-10" style="min-height:220px"><div class="text-center"><i class="fas fa-balance-scale text-2xl mb-2"></i><div>Chưa có mẫu nào trong danh sách so sánh</div></div></div>';
      if (modal) modal.classList.add('hidden');
      return;
    }
    const modal = document.getElementById('compare-modal');
    if (modal && !modal.classList.contains('hidden') && e.target === modal){
      modal.classList.add('hidden');
    }
  });

  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape'){
      const modal = document.getElementById('compare-modal');
      if (modal) modal.classList.add('hidden');
    }
  });

  // Legacy shim for older scripts
  window.updateCompareUI = function(){
    try { refreshButtons(); } catch(_){ }
    try { updateFab(); } catch(_){ }
  };
})();
