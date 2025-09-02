// Ultra-fast Wishlist toggle with optimistic UI and localStorage source-of-truth
(function(){
    const STORAGE_KEY = 'wishlist_items_v2'; // [{t:'car_variant'|'accessory', i:Number}]
    const COUNT_KEY = 'wishlist_count';
    const SELECTORS = ['.wishlist-count', '#wishlist-count-badge', '#wishlist-count-badge-mobile', '[data-wishlist-count]'];

    const state = {
        items: new Map(), // t -> Set(ids)
        recent: new Map(), // key -> ts
        recentWindowMs: 2500
    };

    function key(t, id){ return `${t}:${id}`; }

    function loadFromStorage(){
        state.items.clear();
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return;
            const arr = JSON.parse(raw);
            arr.forEach(({t,i}) => {
                if (!state.items.has(t)) state.items.set(t, new Set());
                state.items.get(t).add(i);
            });
        } catch(_) {}
    }

    function saveToStorage(){
        const arr = [];
        state.items.forEach((set, t) => set.forEach(i => arr.push({ t, i })));
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(arr)); } catch(_) {}
        updateCountBadges();
    }

    function count(){
        let c = 0; state.items.forEach(set => c += set.size); return c;
    }

    function setBadges(c){
        SELECTORS.forEach(sel => {
            document.querySelectorAll(sel).forEach(el => {
                el.textContent = c > 99 ? '99+' : String(c);
                if (c > 0) el.classList.remove('hidden'); else el.classList.add('hidden');
            });
        });
    }

    function updateCountBadges(){
        const c = count();
        try { localStorage.setItem(COUNT_KEY, String(c)); } catch(_) {}
        setBadges(c);
    }

    function isIn(t, id){ return state.items.has(t) && state.items.get(t).has(id); }
    function setIn(t, id){ if (!state.items.has(t)) state.items.set(t, new Set()); state.items.get(t).add(id); }
    function setOut(t, id){ if (state.items.has(t)) state.items.get(t).delete(id); }

    function updateButtons(t, id, inWishlist){
        const btns = document.querySelectorAll(`.js-wishlist-toggle[data-item-type="${t}"][data-item-id="${id}"]`);
        btns.forEach(btn => updateButtonEl(btn, inWishlist));
    }

    function updateButtonEl(button, inWishlist){
        const icon = button.querySelector('i');
        if (inWishlist) {
            button.classList.add('in-wishlist');
            button.classList.remove('not-in-wishlist');
            if (icon) { icon.classList.remove('far'); icon.classList.add('fas'); icon.style.color = '#ef4444'; }
            button.setAttribute('aria-pressed','true');
            button.setAttribute('title','Đã yêu thích');
        } else {
            button.classList.remove('in-wishlist');
            button.classList.add('not-in-wishlist');
            if (icon) { icon.classList.remove('fas'); icon.classList.add('far'); icon.style.color = '#374151'; }
            button.setAttribute('aria-pressed','false');
            button.setAttribute('title','Yêu thích');
        }
    }

    function applyStateToButtons(){
        document.querySelectorAll('.js-wishlist-toggle').forEach(btn => {
            const t = btn.getAttribute('data-item-type');
            const id = parseInt(btn.getAttribute('data-item-id'));
            updateButtonEl(btn, isIn(t, id));
        });
        updateCountBadges();
    }

    function buildStateFromDOM(){
        state.items.clear();
        document.querySelectorAll('.js-wishlist-toggle.in-wishlist').forEach(btn => {
            const t = btn.getAttribute('data-item-type');
            const id = parseInt(btn.getAttribute('data-item-id'));
            if (!state.items.has(t)) state.items.set(t, new Set());
            state.items.get(t).add(id);
        });
    }
    
    async function reconcileWishlistState(){
        try {
            // First try to get all wishlist items from current page DOM
            const wishlistButtons = document.querySelectorAll('.js-wishlist-toggle');
            if (wishlistButtons.length > 0) {
                // Try bulk check first, then fallback to individual checks
                let success = false;
                
                // Try bulk check endpoint
                try {
                    // Group items by type for bulk check
                    const itemsByType = new Map();
                    Array.from(wishlistButtons).forEach(btn => {
                        const t = btn.getAttribute('data-item-type');
                        const id = parseInt(btn.getAttribute('data-item-id'));
                        if (!itemsByType.has(t)) itemsByType.set(t, []);
                        itemsByType.get(t).push(id);
                    });
                    
                    // Check each type separately (since bulk endpoint expects single type)
                    for (const [itemType, itemIds] of itemsByType) {
                        if (itemIds.length > 0) {
                            const response = await fetch('/wishlist/check-bulk', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf(),
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({ 
                                    item_type: itemType, 
                                    item_ids: itemIds 
                                })
                            });
                            
                            if (response.ok) {
                                const data = await response.json();
                                if (data.success && data.existing_ids) {
                                    // Add existing items to state
                                    data.existing_ids.forEach(id => {
                                        if (!state.items.has(itemType)) state.items.set(itemType, new Set());
                                        state.items.get(itemType).add(id);
                                    });
                                }
                            }
                        }
                    }
                    
                    success = true;
                } catch (error) {
                    console.warn('Bulk check failed, trying individual checks:', error);
                }
                
                // Fallback to individual checks if bulk failed
                if (!success) {
                    const promises = Array.from(wishlistButtons).map(async (btn) => {
                        const t = btn.getAttribute('data-item-type');
                        const id = parseInt(btn.getAttribute('data-item-id'));
                        
                        try {
                            const response = await fetch(`/wishlist/check?item_type=${t}&item_id=${id}`, {
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            });
                            
                            if (response.ok) {
                                const data = await response.json();
                                return { type: t, id: id, inWishlist: data.success && data.in_wishlist };
                            }
                        } catch (error) {
                            console.warn(`Failed to check item ${t}:${id}:`, error);
                        }
                        
                        return { type: t, id: id, inWishlist: false };
                    });
                    
                    const results = await Promise.all(promises);
                    
                    // Clear current state and rebuild from individual check results
                    state.items.clear();
                    results.forEach(item => {
                        if (item.inWishlist) {
                            if (!state.items.has(item.type)) state.items.set(item.type, new Set());
                            state.items.get(item.type).add(item.id);
                        }
                    });
                    
                    success = true;
                }
                
                if (success) {
                    // Save to localStorage and update UI
                    saveToStorage();
                    applyStateToButtons();
                    updateCountBadges();
                    return;
                }
            }
        } catch (error) {
            console.warn('Failed to reconcile wishlist state:', error);
        }
        
        // Final fallback: try to load from localStorage and apply to DOM
        loadFromStorage();
        applyStateToButtons();
        updateCountBadges();
    }

    function toast(msg, type='info'){
        if (typeof window.showMessage === 'function') { try { window.showMessage(msg, type); return; } catch(_) {} }
        // Fallback toast with consistent styling
        const colors = { success:'bg-green-500 text-white', error:'bg-red-500 text-white', warning:'bg-yellow-500 text-white', info:'bg-blue-500 text-white' };
        const t = document.createElement('div');
        // Remove any existing toasts to prevent stacking
        const existingToasts = document.querySelectorAll('.toast-notification');
        existingToasts.forEach(toast => toast.remove());

        t.className = `toast-notification ${colors[type]||colors.info}`;
        t.innerHTML = `
            <div class="toast-content">
                <span class="toast-message">${msg}</span>
                <button class="toast-close" aria-label="Đóng" onclick="this.closest('.toast-notification').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        document.body.appendChild(t);
        requestAnimationFrame(()=> t.classList.add('show'));
        setTimeout(()=>{ t.classList.add('hide'); setTimeout(()=>t.remove(), 300); }, 3000);
    }

    function markRecent(t,id){ state.recent.set(key(t,id), Date.now()); setTimeout(()=> state.recent.delete(key(t,id)), state.recentWindowMs); }
    function isRecent(t,id){ const ts = state.recent.get(key(t,id)); return ts && (Date.now()-ts) < state.recentWindowMs; }

    function csrf(){ const m = document.querySelector('meta[name="csrf-token"]'); return m ? m.getAttribute('content') : ''; }

    function sendAdd(t,id){
        fetch('/wishlist/add', { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':csrf(),'X-Requested-With':'XMLHttpRequest' }, body: JSON.stringify({ item_type:t, item_id:id }) })
            .then(r=>r.json()).then(d=>{ if (!d.success) throw new Error(d.message||'error'); if (!isRecent(t,id)) toast(d.message||'Đã thêm vào yêu thích!','success'); if (typeof d.wishlist_count==='number') { try{ localStorage.setItem(COUNT_KEY,String(d.wishlist_count)); }catch(_){} updateCountBadges(); } else { fetch('/wishlist/count',{headers:{'X-Requested-With':'XMLHttpRequest'}}).then(x=>x.json()).then(c=>{ if(c&&c.success&&typeof c.wishlist_count==='number'){ try{ localStorage.setItem(COUNT_KEY,String(c.wishlist_count)); }catch(_){} updateCountBadges(); } }).catch(()=>{}); } })
            .catch(()=>{ // revert on error
                setOut(t,id); saveToStorage(); updateButtons(t,id,false); toast('Có lỗi xảy ra!','error');
            });
    }
    function sendRemove(t,id){
        fetch('/wishlist/remove', { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':csrf(),'X-Requested-With':'XMLHttpRequest' }, body: JSON.stringify({ item_type:t, item_id:id }) })
            .then(r=>r.json()).then(d=>{ if (!d.success) throw new Error(d.message||'error'); if (!isRecent(t,id)) toast(d.message||'Đã xóa khỏi yêu thích!','info'); if (typeof d.wishlist_count==='number') { try{ localStorage.setItem(COUNT_KEY,String(d.wishlist_count)); }catch(_){} updateCountBadges(); } else { fetch('/wishlist/count',{headers:{'X-Requested-With':'XMLHttpRequest'}}).then(x=>x.json()).then(c=>{ if(c&&c.success&&typeof c.wishlist_count==='number'){ try{ localStorage.setItem(COUNT_KEY,String(c.wishlist_count)); }catch(_){} updateCountBadges(); } }).catch(()=>{}); }
                // If on wishlist page, remove the item's card and update empty state
                try {
                    if (window.location.pathname.includes('/wishlist')) {
                        removeItemFromWishlistPage(t, id);
                    }
                } catch(_) {}
            })
            .catch(()=>{ // revert on error
                setIn(t,id); saveToStorage(); updateButtons(t,id,true); toast('Có lỗi xảy ra!','error');
            });
    }

    // ----- Wishlist page helpers -----
    function updateWishlistHeroCount(){
        const remaining = document.querySelectorAll('.wishlist-item, .cart-item').length;
        const heroCount = document.querySelector('span.text-white.font-semibold');
        if (heroCount) { heroCount.textContent = `${remaining} sản phẩm yêu thích`; }
        return remaining;
    }

    function showWishlistEmptyState(){
        let empty = document.getElementById('empty-state');
        if (!empty) {
            // Create empty-state right after the grid
            const grid = document.getElementById('wishlist-grid');
            empty = document.createElement('div');
            empty.id = 'empty-state';
            empty.className = 'text-center py-16';
            if (grid && grid.parentElement) {
                grid.parentElement.insertBefore(empty, grid.nextSibling);
            } else {
                document.body.appendChild(empty);
            }
        }
        empty.innerHTML = `
            <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-8 flex items-center justify-center">
                <i class="fas fa-heart text-gray-400 text-5xl"></i>
            </div>
            <h3 class="text-2xl font-semibold text-gray-600 mb-4">Danh sách yêu thích trống</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">Bạn chưa có sản phẩm nào trong danh sách yêu thích. Hãy khám phá các sản phẩm và thêm vào yêu thích!</p>
            <a href="/" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-shopping-bag mr-2"></i>
                Khám phá sản phẩm
            </a>`;
        empty.style.display = '';
        empty.classList.remove('hidden');
        // Hide filtered empty state if visible
        const filtered = document.getElementById('filtered-empty-state');
        if (filtered) filtered.style.display = 'none';
        const filter = document.getElementById('filter-section');
        if (filter) filter.style.display = 'none';
        const clearBtn = document.getElementById('clear-all-btn');
        const clearWrap = clearBtn && clearBtn.closest('.flex');
        if (clearWrap) clearWrap.style.display = 'none';
        // Ensure grid stays present (not hidden) even if empty, so layout remains
        const grid = document.getElementById('wishlist-grid');
        if (grid) grid.style.display = '';
    }

    function removeItemFromWishlistPage(t,id){
        const selectors = [
            `.wishlist-item[data-item-type="${t}"][data-item-id="${id}"]`,
            `.cart-item[data-item-type="${t}"][data-item-id="${id}"]`,
            `[data-item-id="${id}"][data-item-type="${t}"]`
        ];
        let removed = false;
        selectors.forEach(sel => {
            document.querySelectorAll(sel).forEach(el => { el.parentElement ? el.parentElement.removeChild(el) : el.remove(); removed = true; });
        });
        if (!removed) return;
        const remaining = updateWishlistHeroCount();
        // If filter is active, re-apply filter logic to decide correct empty state
        const filter = document.getElementById('filter-type');
        if (filter && filter.value) {
            applyWishlistFilter();
        } else if (remaining === 0) {
            showWishlistEmptyState();
        }
        if (window.wishlistPage && typeof window.wishlistPage.checkAndUpdateUI === 'function') {
            try { window.wishlistPage.checkAndUpdateUI(); } catch(_) {}
        }
    }

    // ----- Wishlist page: filter and clear-all -----
    function getFilterType(){
        const el = document.getElementById('filter-type');
        return el ? String(el.value || '') : '';
    }

    function applyWishlistFilter(){
        const type = getFilterType();
        const items = Array.from(document.querySelectorAll('.wishlist-item'));
        const total = items.length;
        let visible = 0;
        items.forEach(item => {
            const t = item.getAttribute('data-type');
            const show = !type || type === t;
            item.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        // Update hero count to visible
        const heroCount = document.querySelector('span.text-white.font-semibold');
        if (heroCount) heroCount.textContent = `${visible} sản phẩm yêu thích`;
        // Handle filtered empty state
        if (visible === 0 && total > 0) {
            showWishlistFilteredEmptyState(type);
        } else {
            hideWishlistFilteredEmptyState();
        }
        // Ensure filter area and clear-all visibility
        const filter = document.getElementById('filter-section');
        if (filter) filter.style.display = '';
        const clearBtn = document.getElementById('clear-all-btn');
        const wrap = clearBtn && clearBtn.closest('.flex');
        if (wrap) wrap.style.display = '';
    }

    function showWishlistFilteredEmptyState(type){
        let node = document.getElementById('filtered-empty-state');
        const label = type === 'accessory' ? 'Phụ kiện' : (type === 'car_variant' ? 'Xe hơi' : 'Sản phẩm');
        const title = type ? `Không có ${label.toLowerCase()} trong bộ lọc này` : 'Không có sản phẩm phù hợp với bộ lọc hiện tại';
        const msg = type ? `Hiện không có ${label.toLowerCase()} nào trong danh sách yêu thích theo bộ lọc “${label}”. Bạn có thể xem tất cả.` : 'Hãy thử thay đổi bộ lọc hoặc sắp xếp!';
        if (!node) {
            node = document.createElement('div');
            node.id = 'filtered-empty-state';
            node.className = 'text-center py-16';
            node.innerHTML = `
                <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-8 flex items-center justify-center">
                    <i class="fas fa-search text-gray-400 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-600 mb-4"></h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto"></p>
                <div class="flex items-center justify-center gap-3">
                    <button id="btn-reset-filter" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg">
                        <i class="fas fa-layer-group mr-2"></i>
                        Xem tất cả
                    </button>
                    <button id="btn-change-filter" class="inline-flex items-center bg-white text-gray-700 px-6 py-3 rounded-xl font-semibold border border-gray-200 hover:bg-gray-50 transition-all duration-300">
                        <i class="fas fa-sliders-h mr-2"></i>
                        Thay đổi bộ lọc
                    </button>
                </div>`;
            const grid = document.getElementById('wishlist-grid');
            if (grid && grid.parentElement) grid.parentElement.insertBefore(node, grid.nextSibling);
            // Bind buttons once
            document.addEventListener('click', function(ev){
                const target = ev.target.closest('#btn-reset-filter');
                if (target) {
                    const select = document.getElementById('filter-type');
                    if (select) select.value = '';
                    applyWishlistFilter();
                }
                const changeBtn = ev.target.closest('#btn-change-filter');
                if (changeBtn) {
                    const select = document.getElementById('filter-type');
                    if (select) select.focus();
                }
            });
        }
        node.querySelector('h3').textContent = title;
        node.querySelector('p').textContent = msg;
        node.style.display = '';
        // Hide base empty state if exists
        const empty = document.getElementById('empty-state');
        if (empty) empty.style.display = 'none';
    }

    function hideWishlistFilteredEmptyState(){
        const node = document.getElementById('filtered-empty-state');
        if (node) node.style.display = 'none';
    }

    function handleClearAllClick(e){
        e && e.preventDefault && e.preventDefault();
        showConfirmDialog(
            'Xóa tất cả sản phẩm yêu thích?',
            'Bạn có chắc chắn muốn xóa tất cả sản phẩm khỏi danh sách yêu thích? Hành động này không thể hoàn tác.',
            'Xóa tất cả',
            'Hủy bỏ',
            () => {
                const btn = document.getElementById('clear-all-btn');
                if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xóa...'; }
                // Optimistic: clear DOM
                document.querySelectorAll('.wishlist-item, .cart-item').forEach(el => el.remove());
                // Clear state and badges
                state.items.clear(); saveToStorage(); updateCountBadges();
                showWishlistEmptyState();
                // Reset filter selection if present
                const select = document.getElementById('filter-type');
                if (select) select.value = '';
                // Server call
                fetch('/wishlist/clear', { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':csrf(),'X-Requested-With':'XMLHttpRequest' } })
                    .then(r=>r.json())
                    .then(d=>{ if (!d.success) throw new Error(); })
                    .catch(()=>{ /* if server fails, keep empty UI; next navigation will resync */ })
                    .finally(()=>{ if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-trash mr-2"></i>Xóa tất cả'; } });
            }
        );
    }

    function bindWishlistPageEvents(){
        const filter = document.getElementById('filter-type');
        if (filter && !filter.__fastBound) {
            filter.addEventListener('change', applyWishlistFilter);
            filter.__fastBound = true;
        }
        const clearBtn = document.getElementById('clear-all-btn');
        if (clearBtn && !clearBtn.__fastBound) {
            clearBtn.addEventListener('click', handleClearAllClick);
            clearBtn.__fastBound = true;
        }
        // Initial apply
        if (filter) applyWishlistFilter();
    }

    // Simple modern confirmation dialog
    function showConfirmDialog(title, message, confirmText, cancelText, onConfirm){
        // Remove any existing
        const existing = document.querySelector('.fast-confirm-dialog');
        if (existing) existing.remove();
        const wrapper = document.createElement('div');
        wrapper.className = 'fast-confirm-dialog fixed inset-0 z-[100000] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4';
        wrapper.innerHTML = `
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0">
                <div class="p-6">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">${title}</h3>
                    <p class="text-gray-600 text-center mb-6">${message}</p>
                    <div class="flex space-x-3">
                        <button class="fast-cancel flex-1 px-4 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">${cancelText}</button>
                        <button class="fast-confirm flex-1 px-4 py-2.5 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors duration-200">${confirmText}</button>
                    </div>
                </div>
            </div>`;
        document.body.appendChild(wrapper);
        const panel = wrapper.firstElementChild;
        requestAnimationFrame(()=>{ panel.classList.remove('scale-95','opacity-0'); panel.classList.add('scale-100','opacity-100'); });
        function close(){ wrapper.remove(); }
        wrapper.addEventListener('click', (ev)=>{ if (ev.target === wrapper) close(); });
        wrapper.querySelector('.fast-cancel').addEventListener('click', close);
        wrapper.querySelector('.fast-confirm').addEventListener('click', ()=>{ close(); onConfirm && onConfirm(); });
        document.addEventListener('keydown', function esc(e){ if (e.key==='Escape'){ close(); document.removeEventListener('keydown', esc); } });
    }

    function onToggleClick(e){
        const btn = e.target.closest('.js-wishlist-toggle');
        if (!btn) return;
        e.preventDefault();
        const t = btn.getAttribute('data-item-type');
        const id = parseInt(btn.getAttribute('data-item-id'));
        const currently = isIn(t, id);
        markRecent(t,id);
        if (currently){
            setOut(t,id); saveToStorage(); updateButtons(t,id,false); toast('Đã xóa khỏi yêu thích','info'); sendRemove(t,id);
        } else {
            setIn(t,id); saveToStorage(); updateButtons(t,id,true); toast('Đã thêm vào yêu thích','success'); sendAdd(t,id);
        }
    }

    let __fastInitBound = false;
    function init(){
        // First, try to load from localStorage to restore state
        loadFromStorage();
        
        // Then check if we need to reconcile with server
        const needsReconcile = !state.items.size || document.querySelectorAll('.js-wishlist-toggle.in-wishlist').length === 0;
        
        if (needsReconcile) {
            // If no localStorage data or no DOM buttons, fetch from server
            reconcileWishlistState();
        } else {
            // Apply stored state to buttons
            applyStateToButtons();
        }
        
        // Update count badges
        updateCountBadges();
        
        if (!__fastInitBound) {
            document.addEventListener('click', onToggleClick);
            __fastInitBound = true;
        }
        
        // Bind wishlist page specific events if present
        bindWishlistPageEvents();
        
        window.addEventListener('storage', (e)=>{
            if (e.key === STORAGE_KEY || e.key === COUNT_KEY){
                loadFromStorage();
                applyStateToButtons();
                bindWishlistPageEvents();
            }
        });
        
        window.addEventListener('pageshow', ()=>{
            // On page show, always reconcile to ensure accuracy
            reconcileWishlistState();
            
            // Also reconcile counts from server when page is shown
            if (window.CountManager) {
                window.CountManager.reconcileCart();
                window.CountManager.reconcileWishlist();
            }
        });

        // Compatibility shims for existing code paths
        window.wishlistManager = window.wishlistManager || {
            updateCount: (c)=>{ try{ localStorage.setItem(COUNT_KEY, String(c)); }catch(_){} setBadges(c); },
            checkWishlistStatus: ()=>{ /* no-op: fast module uses local state */ }
        };
        window.refreshWishlistStatus = function(){ reconcileWishlistState(); };
    }

    if (document.readyState === 'loading'){
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();


