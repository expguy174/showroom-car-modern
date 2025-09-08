// Ultra-fast Wishlist toggle with optimistic UI and localStorage source-of-truth
(function(){
    const STORAGE_KEY = 'wishlist_items'; // [{t:'car_variant'|'accessory', i:Number}]
    const COUNT_KEY = 'wishlist_count';
    const SELECTORS = [
        '.wishlist-count', 
        '#wishlist-count-badge', 
        '#wishlist-count-badge-mobile', 
        '[data-wishlist-count]',
        '.wishlist-count-badge',
        '.js-wishlist-count'
    ];

    const state = {
        items: new Map(), // t -> Set(ids)
        recent: new Map(), // key -> ts
        recentWindowMs: 500, // Reduced to 500ms for better UX
        processing: new Set(), // Track items being processed to prevent race conditions
        reconciling: false, // Track if reconciliation is in progress
        lastReconcile: 0, // Timestamp of last reconciliation
        updatingCount: false, // Prevent multiple simultaneous count updates
        lastCountUpdate: 0, // Timestamp of last count update
        countUpdateDebounceMs: 100, // Debounce count updates
        // Cache DOM elements for better performance
        cachedElements: new Map(),
        // Batch operations for better performance
        batchOperations: new Set(),
        batchTimeout: null
    };

    // Optimized key generation with caching and cleanup
    const keyCache = new Map();
    const MAX_CACHE_SIZE = 1000; // Prevent memory leak
    
    function key(t, id){ 
        const cacheKey = `${t}:${id}`;
        if (!keyCache.has(cacheKey)) {
            // Clean cache if it gets too large
            if (keyCache.size >= MAX_CACHE_SIZE) {
                keyCache.clear();
            }
            keyCache.set(cacheKey, cacheKey);
        }
        return keyCache.get(cacheKey);
    }

    function loadFromStorage(){
        state.items.clear();
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return;
            const arr = JSON.parse(raw);
            
            // Optimized batch processing
            const typeMap = new Map();
            arr.forEach(({t,i}) => {
                if (!typeMap.has(t)) typeMap.set(t, new Set());
                typeMap.get(t).add(i);
            });
            
            // Batch set operations
            typeMap.forEach((ids, type) => {
                state.items.set(type, ids);
            });
            
            // Sync count with items - ensure both keys are in sync
            const count = arr.length;
            const storedCount = parseInt(localStorage.getItem(COUNT_KEY) || '0', 10);
            if (count !== storedCount) {
                try { localStorage.setItem(COUNT_KEY, String(count)); } catch(_) {}
            }
        } catch(_) {}
    }

    function saveToStorage(){
        // Optimized array building
        const arr = [];
        for (const [t, set] of state.items) {
            for (const i of set) {
                arr.push({ t, i });
            }
        }
        
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(arr)); } catch(_) {}
        
        // Force update count in localStorage immediately and push to global badge helper
        const currentCount = count();
        try { localStorage.setItem(COUNT_KEY, String(currentCount)); } catch(_) {}
        try { window.WishlistCount && window.WishlistCount.apply(currentCount); } catch(_) {}
        
        // Update count badges immediately - but only if not already updating
        if (!state.updatingCount) {
        updateCountBadges();
        }
    }

    function count(){
        let c = 0; 
        for (const set of state.items.values()) {
            c += set.size;
        }
        return c;
    }

    function updateCountBadges(){
        // Prevent multiple simultaneous updates
        if (state.updatingCount) {
            return;
        }
        
        // Debounce rapid successive calls
        const now = Date.now();
        if (now - state.lastCountUpdate < state.countUpdateDebounceMs) {
            return;
        }
        
        state.updatingCount = true;
        state.lastCountUpdate = now;
        
        try {
        const c = count();
        
            // Always update localStorage to ensure consistency
        try { 
            localStorage.setItem(COUNT_KEY, String(c)); 
            localStorage.setItem(STORAGE_KEY, c === 0 ? '[]' : JSON.stringify(Array.from(state.items.entries()).flatMap(([type, ids]) => 
                Array.from(ids).map(id => ({ t: type, i: id }))
            )));
        } catch(_) {}
        
        // Update DOM directly with all possible selectors
            if (window.paintBadge) { window.paintBadge(SELECTORS, c); }
        
        // Also update any other count elements that might exist
        document.querySelectorAll('[class*="wishlist"][class*="count"], [id*="wishlist"][id*="count"]').forEach(el => {
            if (el.textContent && !isNaN(parseInt(el.textContent))) {
                const currentText = el.textContent;
                const newText = c > 99 ? '99+' : String(c);
                
                    // Always update to ensure consistency
                    el.textContent = newText;
                
                if (c > 0) {
                    el.classList.remove('hidden');
                    el.style.display = '';
                } else {
                    el.classList.add('hidden');
                    el.style.display = 'none';
                }
            }
        });
        
            // Guard: if any badge still shows 0 while c>0, force repaint textContent
            if (c > 0) {
                const all = document.querySelectorAll('[data-wishlist-count], .wishlist-count, #wishlist-count-badge, #wishlist-count-badge-mobile, .wishlist-count-badge');
                all.forEach(el => {
                    const txt = (el.textContent||'').trim();
                    if (txt === '0' || txt === '') {
                        el.textContent = c > 99 ? '99+' : String(c);
                        el.classList && el.classList.remove('hidden');
                        if (el.style) el.style.display = '';
                    }
                });
            }
        console.log(`Updated wishlist count to ${c} across all badges`);
        } finally {
            state.updatingCount = false;
        }
    }

    function forceClearWishlistStorage() {
        // Force clear all wishlist related localStorage
        try {
            localStorage.removeItem(STORAGE_KEY);
            localStorage.removeItem(COUNT_KEY);
            localStorage.setItem(STORAGE_KEY, '[]');
            localStorage.setItem(COUNT_KEY, '0');
        } catch(_) {}
        
        // Force update all count badges to 0
        const allSelectors = [...SELECTORS,'[class*="wishlist"][class*="count"]','[id*="wishlist"][id*="count"]','[data-wishlist-count]'];
        if (window.paintBadge) { window.paintBadge(allSelectors, 0); }
        
        console.log('Force cleared all wishlist storage and updated UI');
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
            if (icon) { icon.classList.remove('far'); icon.classList.add('fas'); }
            button.setAttribute('aria-pressed','true');
            button.setAttribute('title','Đã yêu thích');
        } else {
            button.classList.remove('in-wishlist');
            button.classList.add('not-in-wishlist');
            if (icon) { icon.classList.remove('fas'); icon.classList.add('far'); }
            button.setAttribute('aria-pressed','false');
            button.setAttribute('title','Yêu thích');
        }
    }

    function applyStateToButtons(){
        document.querySelectorAll('.js-wishlist-toggle').forEach(btn => {
            const t = btn.getAttribute('data-item-type');
            const id = parseInt(btn.getAttribute('data-item-id'));
            const itemKey = key(t, id);
            
            // Skip updating buttons that are currently being processed
            if (state.processing.has(itemKey)) {
                return;
            }
            
            updateButtonEl(btn, isIn(t, id));
        });
        // Update count badges - but only if not already updating
        if (!state.updatingCount) {
        updateCountBadges();
        }
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

    async function getServerCount(){
        try {
            const response = await fetch(`/wishlist/count?t=${Date.now()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Cache-Control': 'no-store' },
                cache: 'no-store'
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    return data.wishlist_count || 0;
                }
            }
        } catch (error) {
            console.warn('Failed to get server count:', error);
        }
        return 0;
    }

    async function fetchAllWishlistFromServer(){
        try {
            // Fetch all wishlist items from server
            const response = await fetch(`/wishlist/items?t=${Date.now()}`, {
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Cache-Control': 'no-store'
                },
                cache: 'no-store'
            });
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.warn('Server returned non-JSON response, likely session expired or server error');
                return false;
            }
            
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.wishlist_items) {
                    // Clear current state
                    state.items.clear();
                    
                    // Add all items from server
                    data.wishlist_items.forEach(item => {
                        const itemType = item.item_type;
                        const itemId = item.item_id;
                        if (!state.items.has(itemType)) {
                            state.items.set(itemType, new Set());
                        }
                        state.items.get(itemType).add(itemId);
                    });
                    
                    console.log(`Loaded ${data.wishlist_items.length} items from server`);
                    return true;
                }
            } else {
                console.warn('Server returned error status:', response.status);
            }
        } catch (error) {
            console.warn('Failed to fetch all wishlist from server:', error);
        }
        return false;
    }

    async function checkServerCountAndReconcile(){
        // Suppress immediate reconcile shortly after a local destructive action (e.g., Clear All)
        try {
            const lastAction = parseInt(localStorage.getItem('wishlist_last_action') || '0', 10);
            const now = Date.now();
            if (lastAction && (now - lastAction) < 1200) {
                return;
            }
        } catch(_) {}
        try {
            // First get server count to detect mismatches
            const serverCount = await getServerCount();
            const localCount = count();
            
            // If counts don't match, we need to reconcile
            if (serverCount !== localCount) {
                console.log(`Wishlist mismatch detected: local=${localCount}, server=${serverCount}. Reconciling...`);
                await reconcileWishlistState();
                // After reconcile, if still zero and on wishlist page, ensure empty shows and stop progress line
                if (count() === 0 && window.location.pathname.includes('/wishlist')) {
                    try {
                        const empty = document.getElementById('empty-state');
                        if (empty) { empty.classList.remove('hidden'); empty.style.display=''; }
                        const barWrap = document.getElementById('filter-progress');
                        const bar = barWrap && barWrap.querySelector('.filter-loading');
                        if (bar) bar.classList.add('hidden');
                    } catch(_) {}
                }
                return;
            }
            
            // If counts match but no local data, still reconcile once; if still zero, show empty
            if (localCount === 0) {
                if (serverCount > 0) { await reconcileWishlistState(); }
                if (count() === 0 && window.location.pathname.includes('/wishlist')) {
                    const empty = document.getElementById('empty-state');
                    if (empty) { empty.classList.remove('hidden'); empty.style.display=''; }
                    try {
                        const barWrap = document.getElementById('filter-progress');
                        const bar = barWrap && barWrap.querySelector('.filter-loading');
                        if (bar) bar.classList.add('hidden');
                    } catch(_) {}
                }
                return;
            }
            
            // If we have local data and counts match, apply it but do background sync
            if (localCount > 0) {
                applyStateToButtons();
                // Background sync to ensure accuracy
                setTimeout(() => {
                    if (!state.processing.size && !state.reconciling) {
                        reconcileWishlistState();
                    }
                }, 1000);
            } else {
                // No data anywhere, just apply empty state and ensure empty UI is visible
                applyStateToButtons();
                try { if (window.location.pathname.includes('/wishlist')) { const empty = document.getElementById('empty-state'); if (empty) empty.classList.remove('hidden'); } } catch(_) {}
            }
        } catch (error) {
            // If server check fails, fallback to local data
            console.warn('Failed to check server count, using local data:', error);
            applyStateToButtons();
        }
    }
    
    async function reconcileWishlistState(){
        // Hard guard: skip reconcile briefly after destructive local actions
        try {
            const lastActionTs = parseInt(localStorage.getItem('wishlist_last_action') || '0', 10);
            if (lastActionTs && (Date.now() - lastActionTs) < 2000) {
                return;
            }
        } catch(_) {}
        // Prevent multiple concurrent reconciliations
        if (state.reconciling) return;
        state.reconciling = true;
        
        try {
            // First, try to get all wishlist items from server if we suspect a mismatch
            const serverCount = await getServerCount();
            const localCount = count();
            
            if (serverCount !== localCount) {
                if (serverCount > localCount) {
                    // Server has more items, fetch all from server
                    console.log(`Fetching all wishlist items from server (server: ${serverCount}, local: ${localCount})`);
                    const success = await fetchAllWishlistFromServer();
                    if (success) {
                        saveToStorage();
                        applyStateToButtons();
                    } else {
                        // If server fetch fails, keep local data
                        console.log('Server fetch failed, keeping local data');
                        applyStateToButtons();
                    }
                } else {
                    // Local has more items, server is source of truth - sync from server
                    console.log(`Local has more items than server (local: ${localCount}, server: ${serverCount}). Syncing from server...`);
                    const success = await fetchAllWishlistFromServer();
                    if (success) {
                        saveToStorage();
                        applyStateToButtons();
                    } else {
                        // If server fetch fails, keep local data
                        console.log('Server fetch failed, keeping local data');
                        applyStateToButtons();
                    }
                }
                state.reconciling = false;
                return;
            }
            
            // Otherwise, proceed with normal reconciliation
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
                    // Bulk check failed, continue with individual checks
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
                            // Failed to check item, assume not in wishlist
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
                    return;
                }
            }
        } catch (error) {
            // Failed to reconcile wishlist state, use fallback
        }
        
        // Final fallback: try to load from localStorage and apply to DOM
        loadFromStorage();
        applyStateToButtons();
        
        // Mark reconciliation as complete
        state.reconciling = false;
    }

    function toast(msg, type='info'){
        if (typeof window.showMessage === 'function') { try { window.showMessage(msg, type); return; } catch(_) {} }
        // Fallback toast with consistent styling
        const colors = { success:'bg-green-500 text-white', error:'bg-red-500 text-white', warning:'bg-yellow-500 text-white', info:'bg-blue-500 text-white' };
        
        // Find existing toast with same message and type
        const existingToasts = document.querySelectorAll('.toast-notification');
        let existingToast = null;
        
        // Look for toast with same content and type
        for (const toast of existingToasts) {
            const toastMessage = toast.querySelector('.toast-message')?.textContent;
            const toastType = toast.className.includes('bg-green-500') ? 'success' :
                             toast.className.includes('bg-red-500') ? 'error' :
                             toast.className.includes('bg-yellow-500') ? 'warning' : 'info';
            
            if (toastMessage === msg && toastType === type) {
                existingToast = toast;
                break;
            }
        }
        
        // If found same toast, remove it first to show new one
        if (existingToast) {
            existingToast.remove();
        }
        
        // Remove any other existing toasts to prevent stacking
        existingToasts.forEach(toast => toast.remove());

        const t = document.createElement('div');
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

    function markRecent(t,id){ 
        const k = key(t,id);
        state.recent.set(k, Date.now()); 
        setTimeout(()=> state.recent.delete(k), state.recentWindowMs); 
    }
    function isRecent(t,id){ const ts = state.recent.get(key(t,id)); return ts && (Date.now()-ts) < state.recentWindowMs; }

    function csrf(){ const m = document.querySelector('meta[name="csrf-token"]'); return m ? m.getAttribute('content') : ''; }

    function sendAdd(t,id){
        fetch(`/wishlist/add?t=${Date.now()}`, { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':csrf(),'X-Requested-With':'XMLHttpRequest', 'Cache-Control':'no-store' }, body: JSON.stringify({ item_type:t, item_id:id }), cache:'no-store' })
            .then(r=>{
                // Check if response is JSON
                const contentType = r.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('session_expired');
                }
                return r.json();
            })
            .then(d=>{ 
                if (!d.success) throw new Error(d.message||'error'); 
                toast(d.message||'Đã thêm vào yêu thích!','success'); 
                // Count already updated by saveToStorage(), no need to update again
                
                // Restore button state after successful server response
                const btn = document.querySelector(`.js-wishlist-toggle[data-item-type="${t}"][data-item-id="${id}"]`);
                if (btn) {
                    btn.disabled = false;
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.className = 'fas fa-heart';
                        // CSS rule will handle the red color for in-wishlist state
                    }
                    btn.classList.add('in-wishlist');
                    btn.classList.remove('not-in-wishlist');
                }
                state.processing.delete(key(t,id));
            })
            .catch((error)=>{
                if (error.message === 'session_expired') {
                    // Session expired - keep optimistic update and show offline message
                    toast('Đã thêm vào yêu thích (chế độ offline)','info');
                    const btn = document.querySelector(`.js-wishlist-toggle[data-item-type="${t}"][data-item-id="${id}"]`);
                    if (btn) {
                        btn.disabled = false;
                        const icon = btn.querySelector('i');
                        if (icon) {
                            icon.className = 'fas fa-heart';
                        }
                        btn.classList.add('in-wishlist');
                        btn.classList.remove('not-in-wishlist');
                    }
                    state.processing.delete(key(t,id));
                } else {
                    // Other error - revert optimistic update
                    setOut(t,id); saveToStorage(); updateButtons(t,id,false); toast('Có lỗi xảy ra!','error');
                    const btn = document.querySelector(`.js-wishlist-toggle[data-item-type="${t}"][data-item-id="${id}"]`);
                    if (btn) {
                        btn.disabled = false;
                        const icon = btn.querySelector('i');
                        if (icon) {
                            icon.className = 'far fa-heart';
                        }
                        btn.classList.remove('in-wishlist');
                        btn.classList.add('not-in-wishlist');
                    }
                    state.processing.delete(key(t,id));
                }
            });
    }
    function sendRemove(t,id){
        // CRITICAL: Update state immediately for optimistic UI
        setOut(t, id);
        saveToStorage();
        try { localStorage.setItem('wishlist_last_action', String(Date.now())); } catch(_) {}
        
        // Optimistic update - remove from DOM immediately if on wishlist page
        if (window.location.pathname.includes('/wishlist')) {
            const itemElement = document.querySelector(`.wishlist-item[data-item-type="${t}"][data-item-id="${id}"]`);
            if (itemElement) {
                // Fade out and remove item
                itemElement.style.transition = 'all 0.3s ease';
                itemElement.style.opacity = '0';
                itemElement.style.transform = 'scale(0.9)';
                
                setTimeout(() => {
                    if (itemElement.parentElement) {
                        itemElement.parentElement.removeChild(itemElement);
                        
                        // Update hero count and check if empty
                        const remaining = updateWishlistHeroCount();
                        const totalCount = count();
                        const form = document.getElementById('filter-form');
                        const typeSel = form ? (form.querySelector('select[name="type"]')?.value || '') : '';
                        if (remaining === 0) {
                            if (typeSel && totalCount > 0) {
                                // Only current filter became empty -> show filtered empty
                                const baseEmpty = document.getElementById('empty-state');
                                if (baseEmpty) { baseEmpty.classList.add('hidden'); baseEmpty.style.display = 'none'; }
                                showFilteredTypeEmptyIfNeeded();
                            } else {
                                // Whole wishlist empty
                                const filteredNode = document.getElementById('filtered-empty-state');
                                if (filteredNode) { filteredNode.style.display = 'none'; }
                            showWishlistEmptyState();
                            }
                        } else {
                            showFilteredTypeEmptyIfNeeded();
                        }
                    }
                }, 300);
            }
        }
        
        fetch(`/wishlist/remove?t=${Date.now()}`, { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':csrf(),'X-Requested-With':'XMLHttpRequest', 'Cache-Control':'no-store' }, body: JSON.stringify({ item_type:t, item_id:id }), cache:'no-store' })
            .then(r=>{
                // Check if response is JSON
                const contentType = r.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('session_expired');
                }
                return r.json();
            })
            .then(d=>{ 
                if (!d.success) throw new Error(d.message||'error'); 
                toast(d.message||'Đã xóa khỏi yêu thích!','info'); 
                
                // Count already updated by saveToStorage(), no need to update again
                
                // Restore button state after successful server response
                const btn = document.querySelector(`.js-wishlist-toggle[data-item-type="${t}"][data-item-id="${id}"]`);
                if (btn) {
                    btn.disabled = false;
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.className = 'far fa-heart';
                        // CSS rule will handle the gray color for not-in-wishlist state
                    }
                    btn.classList.remove('in-wishlist');
                    btn.classList.add('not-in-wishlist');
                }
                // Immediately fetch all from server to ensure LS and badges are correct
                fetchAllWishlistFromServer().then((ok)=>{
                    if (ok) {
                        saveToStorage();
                        applyStateToButtons();
                        if (window.location.pathname.includes('/wishlist')) {
                            const visibleNow = document.querySelectorAll('.wishlist-item').length;
                            const totalNow = count();
                            const form = document.getElementById('filter-form');
                            const typeSel = form ? (form.querySelector('select[name="type"]')?.value || '') : '';
                            if (visibleNow === 0) {
                                if (typeSel && totalNow > 0) {
                                    const baseEmpty = document.getElementById('empty-state');
                                    if (baseEmpty) { baseEmpty.classList.add('hidden'); baseEmpty.style.display = 'none'; }
                                    showFilteredTypeEmptyIfNeeded();
                                } else {
                                    const filteredNode = document.getElementById('filtered-empty-state');
                                    if (filteredNode) { filteredNode.style.display = 'none'; }
                                    showWishlistEmptyState();
                                }
                            } else {
                                showFilteredTypeEmptyIfNeeded();
                            }
                        }
                    } else {
                        reconcileWishlistState();
                    }
                }).finally(()=>{
                state.processing.delete(key(t,id));
                });
            })
            .catch((error)=>{
                if (error.message === 'session_expired') {
                    // Session expired - keep optimistic update and show offline message
                    toast('Đã xóa khỏi yêu thích (chế độ offline)','info');
                    const btn = document.querySelector(`.js-wishlist-toggle[data-item-type="${t}"][data-item-id="${id}"]`);
                    if (btn) {
                        btn.disabled = false;
                        const icon = btn.querySelector('i');
                        if (icon) {
                            icon.className = 'far fa-heart';
                        }
                        btn.classList.remove('in-wishlist');
                        btn.classList.add('not-in-wishlist');
                    }
                    
                    // If on wishlist page, still remove the item's card
                    if (window.location.pathname.includes('/wishlist')) {
                        setTimeout(() => {
                            try {
                                // Direct DOM removal if on wishlist page
                                const itemElement = document.querySelector(`.wishlist-item[data-item-type="${t}"][data-item-id="${id}"]`);
                                if (itemElement && itemElement.parentElement) {
                                    itemElement.parentElement.removeChild(itemElement);
                                    updateWishlistHeroCount();
                                    const remaining = document.querySelectorAll('.wishlist-item').length;
                                    if (remaining === 0) {
                                        showWishlistEmptyState();
                                    }
                                }
                            } catch(e) {
                                // Error removing item from wishlist page
                            }
                        }, 100);
                    }
                    
                    state.processing.delete(key(t,id));
                } else {
                    // Other error - revert optimistic update
                    setIn(t,id); saveToStorage(); updateButtons(t,id,true); toast('Có lỗi xảy ra!','error');
                    const btn = document.querySelector(`.js-wishlist-toggle[data-item-type="${t}"][data-item-id="${id}"]`);
                    if (btn) {
                        btn.disabled = false;
                        const icon = btn.querySelector('i');
                        if (icon) {
                            icon.className = 'fas fa-heart';
                        }
                        btn.classList.add('in-wishlist');
                        btn.classList.remove('not-in-wishlist');
                    }
                    
                    // If on wishlist page, reload to restore state
                    if (window.location.pathname.includes('/wishlist')) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                    
                    state.processing.delete(key(t,id));
                }
            });
    }

    // ----- Wishlist page helpers -----
    function updateWishlistHeroCount(){
        const remaining = document.querySelectorAll('.wishlist-item').length;
        const heroCount = document.querySelector('span.text-white.font-semibold');
        if (heroCount) { heroCount.textContent = `${remaining} sản phẩm yêu thích`; }
        
        // CRITICAL: Also update count badges to keep them in sync
        updateCountBadges();
        
        return remaining;
    }

    function showWishlistEmptyState(){
        // Only render base empty state on the wishlist page
        if (!window.location.pathname.includes('/wishlist')) {
            return;
        }
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
                // If grid missing (unexpected), do nothing on non-wishlist pages
                return;
            }
        }
        empty.innerHTML = `
            <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-8 flex items-center justify-center">
                <i class="fas fa-heart text-gray-400 text-5xl"></i>
            </div>
            <h3 class="text-2xl font-semibold text-gray-600 mb-4">Danh sách yêu thích trống</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">Bạn chưa có sản phẩm nào trong danh sách yêu thích. Hãy khám phá các sản phẩm và thêm vào yêu thích!</p>
            <a href="/products" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-shopping-bag mr-2"></i>
                Khám phá sản phẩm
            </a>`;
        // Ensure both count and UI reflect empty immediately
        try { localStorage.setItem(COUNT_KEY, '0'); } catch(_) {}
        try { window.WishlistCount && window.WishlistCount.apply(0); } catch(_) {}
        updateCountBadges();
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

    function showFilteredTypeEmptyIfNeeded(){
        try {
            if (!window.location.pathname.includes('/wishlist')) return;
            const form = document.getElementById('filter-form');
            const typeSel = form ? (form.querySelector('select[name="type"]')?.value || '') : '';
            if (!typeSel) return;
            const gridNode = document.getElementById('wishlist-grid');
            if (!gridNode) return;
            const remainingOfType = gridNode.querySelectorAll(`.wishlist-item[data-type="${typeSel}"]`).length;
            if (remainingOfType === 0) {
                const label = typeSel === 'accessory' ? 'Phụ kiện' : 'Xe hơi';
                gridNode.innerHTML = `<div class="col-span-full"><div class="text-center py-16 w-full max-w-md mx-auto">
                    <i class=\"fas fa-search text-3xl text-gray-300\"></i>
                    <div class=\"mt-3 font-semibold text-gray-900\">Không có ${label.toLowerCase()} phù hợp</div>
                    <div class=\"text-sm text-gray-600\">Không tìm thấy ${label.toLowerCase()} phù hợp. Hãy thử thay đổi bộ lọc hoặc từ khóa.</div>
                </div></div>`;
                const pag = document.getElementById('wishlist-pagination');
                if (pag) { pag.innerHTML = ''; pag.style.display = 'none'; }
            }
        } catch(_) {}
    }

    // Function removed - logic moved inline where needed

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
                
                // Clear state first
                state.items.clear();
                console.log('Clear All: State cleared, items count:', state.items.size);
                
                // Force clear all wishlist storage and UI immediately
                forceClearWishlistStorage();
                try { localStorage.setItem('wishlist_last_action', String(Date.now())); } catch(_) {}
                
                // Apply count update globally without waiting
                try { window.WishlistCount && window.WishlistCount.apply(0); } catch(_) {}
                
                // Clear DOM - use correct selector for wishlist items
                const itemsToRemove = document.querySelectorAll('.wishlist-item');
                console.log('Clear All: Found', itemsToRemove.length, 'items to remove from DOM');
                itemsToRemove.forEach(el => el.remove());
                
                // Extra safety: if grid still has residual nodes, empty it
                const gridNode = document.getElementById('wishlist-grid');
                if (gridNode && gridNode.querySelectorAll('.wishlist-item').length > 0) {
                    console.log('Clear All: Grid still has items, forcing innerHTML clear');
                    gridNode.innerHTML = '';
                }
                
                // Force show ONLY the base empty state (hide any filtered-empty)
                const filteredNode = document.getElementById('filtered-empty-state');
                if (filteredNode) { filteredNode.style.display = 'none'; }
                showWishlistEmptyState();
                try {
                    const pag = document.getElementById('wishlist-pagination');
                    if (pag) { pag.innerHTML = ''; pag.style.display = 'none'; }
                } catch(_) {}
                
                // Force update all badges globally
                updateCountBadges();
                
                // Force localStorage sync
                try { 
                    localStorage.setItem(STORAGE_KEY, '[]'); 
                    localStorage.setItem(COUNT_KEY, '0'); 
                    console.log('Clear All: localStorage forced to empty arrays');
                } catch(_) {}
                
                // Verify final state
                const finalItems = document.querySelectorAll('.wishlist-item');
                const finalCount = localStorage.getItem(COUNT_KEY);
                console.log('Clear All: Final DOM items:', finalItems.length, 'Final localStorage count:', finalCount);
                
                // Reset filter selection if present
                const select = document.getElementById('filter-type');
                if (select) select.value = '';
                
                // Server call (no-store to avoid caches/CDN)
                fetch(`/wishlist/clear?t=${Date.now()}`, { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':csrf(),'X-Requested-With':'XMLHttpRequest', 'Cache-Control':'no-store' }, cache:'no-store' })
                    .then(r=>r.json())
                    .then(d=>{ 
                        if (!d.success) throw new Error(d.message||'error');
                        // Show success toast
                        toast('Đã xóa toàn bộ danh sách yêu thích','success');
                        // Ensure localStorage is still clear after server response
                        if (state.items.size === 0) {
                            localStorage.setItem(STORAGE_KEY, '[]');
                            localStorage.setItem(COUNT_KEY, '0');
                            updateCountBadges();
                            try { window.WishlistCount && window.WishlistCount.apply(0); } catch(_) {}
                        }
                        // Short retry loop to confirm server-side emptiness
                        const tryConfirmEmpty = async (attempt = 1) => {
                            const ok = await fetchAllWishlistFromServer();
                            const c = count();
                            if (ok && c === 0) {
                                saveToStorage();
                                updateCountBadges();
                                try { window.WishlistCount && window.WishlistCount.apply(0); } catch(_) {}
                                return true;
                            }
                            if (attempt < 3) {
                                return new Promise(res => setTimeout(async () => res(await tryConfirmEmpty(attempt + 1)), 200));
                            }
                            // Finalize UI as empty even if server still not caught up; next reconcile will fix if needed
                            state.items.clear();
                            saveToStorage();
                            updateCountBadges();
                            try { window.WishlistCount && window.WishlistCount.apply(0); } catch(_) {}
                            return false;
                        };
                        tryConfirmEmpty();
                    })
                    .catch((error)=> { 
                        // If server fails, keep the optimistic update
                        // But ensure localStorage is still updated
                        toast('Có lỗi xảy ra khi xóa danh sách yêu thích!','error');
                        if (state.items.size === 0) {
                            localStorage.setItem(STORAGE_KEY, '[]');
                            localStorage.setItem(COUNT_KEY, '0');
                            updateCountBadges();
                            try { window.WishlistCount && window.WishlistCount.apply(0); } catch(_) {}
                        }
                        // Force empty state even on error
                        showWishlistEmptyState();
                    })
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
        
        const t = String(btn.getAttribute('data-item-type') || '');
        const id = parseInt(btn.getAttribute('data-item-id'));
        if (!t || !Number.isFinite(id) || id <= 0) {
            toast('Dữ liệu không hợp lệ!', 'error');
            return;
        }
        const itemKey = key(t, id);
        
        // Prevent multiple rapid clicks on the same item
        if (state.processing.has(itemKey)) {
            return;
        }
        
        const currently = isIn(t, id);
        
        // Mark as processing
        state.processing.add(itemKey);
        
        // Disable button temporarily to prevent rapid clicks
        btn.disabled = true;
        const originalContent = btn.innerHTML;
        
        // Add loading state with RED spinner
        const icon = btn.querySelector('i');
        if (icon) {
            icon.className = 'fas fa-spinner fa-spin';
            // CSS rule will handle the red color for spinner
        }
        
        // Debounce very fast double-clicks
        if (isRecent(t, id)) {
            btn.disabled = false;
            if (icon) icon.className = currently ? 'fas fa-heart' : 'far fa-heart';
            state.processing.delete(itemKey);
            return;
        }
        markRecent(t, id);
        
        // Optimistic update
        if (currently) {
            setOut(t, id);
            saveToStorage();
            try { window.WishlistCount && window.WishlistCount.apply(count()); } catch(_) {}
            try { localStorage.setItem('wishlist_last_action', String(Date.now())); } catch(_) {}
            // Don't call updateButtons here to keep spinner visible
            sendRemove(t, id);
        } else {
            setIn(t, id);
            saveToStorage();
            try { window.WishlistCount && window.WishlistCount.apply(count()); } catch(_) {}
            try { localStorage.setItem('wishlist_last_action', String(Date.now())); } catch(_) {}
            // Don't call updateButtons here to keep spinner visible
            sendAdd(t, id);
        }
        
        // Safety timeout: ensure we never keep button stuck in processing state
        setTimeout(() => {
            if (state.processing.has(itemKey)) {
                const still = document.querySelector(`.js-wishlist-toggle[data-item-type="${t}"][data-item-id="${id}"]`);
                if (still) {
                    still.disabled = false;
                    const icon2 = still.querySelector('i');
                    if (icon2) icon2.className = currently ? 'far fa-heart' : 'fas fa-heart';
                }
                state.processing.delete(itemKey);
            }
        }, 8000);
    }

    let __fastInitBound = false;
    function init(){

        // First, try to load from localStorage to restore state
        loadFromStorage();
        
        // Sync count with items on init
        const currentCount = count();
        const storedCount = parseInt(localStorage.getItem(COUNT_KEY) || '0', 10);
        if (currentCount !== storedCount) {
            try { localStorage.setItem(COUNT_KEY, String(currentCount)); } catch(_) {}
        }
        
        // Don't call updateCountBadges here - let app.js handle it
        // This prevents duplicate calls on initial load
        
        // Always check server count first to detect mismatches
        checkServerCountAndReconcile();
        
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
        
        window.addEventListener('pageshow', (event)=>{
            // Check if page was restored from cache (back/forward navigation)
            if (event.persisted) {
                // Page was restored from cache, refresh state immediately
                loadFromStorage();
                updateCountBadges();
                applyStateToButtons();
                console.log('Wishlist state refreshed from cache');
            } else {
                // Normal page load, load from storage first
                loadFromStorage();
                // Don't call updateCountBadges here - app.js will handle it
                applyStateToButtons();
            }
            
            // On page show, only reconcile if no operations in progress and not recently reconciled
            if (!state.processing.size) {
                const lastReconcile = state.lastReconcile || 0;
                const now = Date.now();
                if (now - lastReconcile > 1500) { // reconcile sớm hơn để tránh lag cảm giác
                    state.lastReconcile = now;
                    // Delay reconciliation slightly to let UI settle, but check debounce
                    setTimeout(() => {
                        if (Date.now() - state.lastCountUpdate > state.countUpdateDebounceMs) {
                        reconcileWishlistState();
                        }
                    }, 60);
                }
            }
        });

        // Handle browser back/forward navigation
        window.addEventListener('popstate', ()=>{
            // Immediate state refresh to prevent flickering
            loadFromStorage();
            updateCountBadges();
            applyStateToButtons();
            
            // Force immediate count update for instant navigation consistency
            const currentCount = count();
            const storedCount = parseInt(localStorage.getItem(COUNT_KEY) || '0', 10);
            
            // Ensure count is immediately visible and consistent
            if (currentCount !== storedCount) {
                localStorage.setItem(COUNT_KEY, String(currentCount));
                updateCountBadges();
            }
            
            console.log('Wishlist state refreshed after navigation');
        });

        // Handle beforeunload to save state before navigation
        window.addEventListener('beforeunload', ()=>{
            // Ensure state is saved before leaving page
            saveToStorage();
        });
        
        // Handle visibility change (tab focus/blur)
        document.addEventListener('visibilitychange', ()=>{
            if (!document.hidden) {
                // Tab became visible, refresh state
                loadFromStorage();
                // Only update count if not recently updated
                if (Date.now() - state.lastCountUpdate > state.countUpdateDebounceMs) {
                updateCountBadges();
                }
                applyStateToButtons();
            }
        });
        
        // Handle window focus (alternative to visibilitychange)
        window.addEventListener('focus', ()=>{
            // Window gained focus, refresh state
            loadFromStorage();
            // Only update count if not recently updated
            if (Date.now() - state.lastCountUpdate > state.countUpdateDebounceMs) {
            updateCountBadges();
            }
            applyStateToButtons();
        });

        // DOM ready handling is done at the bottom of the file
        // No need to duplicate here

        // Compatibility shims for existing code paths
        window.wishlistManager = {
            updateCount: (c)=>{ 
                try{ localStorage.setItem(COUNT_KEY, String(c)); localStorage.setItem('wishlist_last_action', String(Date.now())); }catch(_){} 
                SELECTORS.forEach(sel => {
                    document.querySelectorAll(sel).forEach(el => {
                        el.textContent = c > 99 ? '99+' : String(c);
                        if (c > 0) el.classList.remove('hidden'); else el.classList.add('hidden');
                    });
                });
            },
            checkWishlistStatus: ()=>{ /* no-op: fast module uses local state */ },
            addItem: (t, id) => { setIn(t, id); saveToStorage(); },
            removeItem: (t, id) => { setOut(t, id); saveToStorage(); },
            getCount: () => count(),
            clearAll: () => { state.items.clear(); saveToStorage(); }
        };
        window.refreshWishlistStatus = function(){ reconcileWishlistState(); };
    }

    if (document.readyState === 'loading'){
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    window.addEventListener('pageshow', function(){
        // Re-apply badge on BFCache return or logo nav instant paint
        try {
            const c = parseInt(localStorage.getItem(COUNT_KEY) || '0', 10);
            if (window.WishlistCount) { window.WishlistCount.apply(c); }
            else if (window.paintBadge) { window.paintBadge(SELECTORS, c); }
        } catch(_) {}
    });
})();

// Lightweight Wishlist Count Sync (simple, robust)
// - Source of truth for instant UX is localStorage('wishlist_count')
// - Server is reconciled in background when needed
// - Common pattern: load from LS on navigation/focus; write to LS on success responses
window.WishlistCount = {
    storageKey: 'wishlist_count',
    initialized: false,
    init() {
        if (this.initialized) return; // Prevent duplicate initialization
        this.initialized = true;
        
        this.load();
        this.guardWindowMs = 1200; // bỏ qua server reconcile trong khoảng ngắn sau hành động local
        // Note: Event listeners are handled by main wishlist manager to avoid duplicates
    },
    load() {
        const count = parseInt(localStorage.getItem(this.storageKey) || '0', 10);
        const lastAction = parseInt(localStorage.getItem('wishlist_last_action') || '0', 10);
        const now = Date.now();
        if (window.wishlistManager && Number.isFinite(count)) {
            if (!lastAction || now - lastAction > this.guardWindowMs) {
            try { window.wishlistManager.updateCount(count); } catch(_) {}
            }
        } else {
            // Fallback update: touch all known selectors
            const selectors = ['.wishlist-count','#wishlist-count-badge','#wishlist-count-badge-mobile','[data-wishlist-count]','.wishlist-count-badge','.js-wishlist-count'];
            selectors.forEach(sel => {
                document.querySelectorAll(sel).forEach(el => {
                    el.textContent = count > 99 ? '99+' : String(count);
                    if (count > 0) { el.classList.remove('hidden'); el.style.display = ''; } else { el.classList.add('hidden'); el.style.display = 'none'; }
                });
            });
        }
    },
    apply(count) {
        const n = parseInt(count || 0, 10);
        try { localStorage.setItem(this.storageKey, String(n)); } catch (_) {}
        // Apply ngay, không đợi guard
        if (window.wishlistManager && Number.isFinite(n)) {
            try { window.wishlistManager.updateCount(n); } catch(_) { this.load(); }
        } else {
        this.load();
        }
    },
    async reconcile() {
        try {
            const r = await fetch('/wishlist/count', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = r.ok ? await r.json() : null;
            if (data && data.success && typeof data.wishlist_count !== 'undefined') {
                const lastAction = parseInt(localStorage.getItem('wishlist_last_action') || '0', 10);
                const now = Date.now();
                if (!lastAction || now - lastAction > this.guardWindowMs) {
                this.apply(data.wishlist_count);
                }
            }
        } catch (_) {}
    }
};


