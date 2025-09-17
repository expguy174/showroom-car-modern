@extends('layouts.app')

@section('title', 'Chi tiết lịch bảo dưỡng')

@section('content')
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
  <div class="flex items-start sm:items-center justify-between gap-3 mb-4 sm:mb-6">
    <div class="min-w-0">
      <div class="flex items-center gap-2 flex-wrap">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight truncate">Lịch bảo dưỡng #{{ $appointment->appointment_number }}</h1>
        <span class="px-2 py-0.5 rounded-full text-xs whitespace-nowrap inline-flex items-center {{ \App\Helpers\ServiceAppointmentHelper::statusBadgeClass($appointment->status) }}" data-role="status-badge">{{ \App\Helpers\ServiceAppointmentHelper::statusLabel($appointment->status) }}</span>
      </div>
      <div class="text-xs sm:text-sm text-gray-500 mt-1">{{ \App\Helpers\ServiceAppointmentHelper::formatDate($appointment->appointment_date) }} {{ \App\Helpers\ServiceAppointmentHelper::formatTime($appointment->appointment_time) }} • {{ $appointment->showroom->name }}</div>
    </div>
    <a href="{{ route('user.service-appointments.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold shrink-0">
      <i class="fas fa-arrow-left"></i> Quay lại
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
    <div class="lg:col-span-2 space-y-4 sm:space-y-6">
      <!-- Thông tin chính -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-indigo-50 flex items-center justify-between">
          <h2 class="text-lg font-bold">Chi tiết lịch bảo dưỡng</h2>
          <div class="text-xs text-gray-500">Cập nhật lúc {{ $appointment->updated_at?->format('d/m/Y H:i') }}</div>
        </div>
        <div class="p-4 sm:p-6">
          <!-- Dịch vụ và lịch hẹn -->
          @if($appointment->service)
          <div class="flex items-start gap-4 mb-6">
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
              <i class="fas fa-tools text-indigo-600 text-lg"></i>
            </div>
            <div class="min-w-0 flex-1">
              <div class="text-gray-700 text-sm">Dịch vụ</div>
              <div class="font-semibold text-gray-900 mb-1">{{ $appointment->service->name }}</div>
              <div class="text-sm text-gray-600 mb-3">{{ $appointment->service->description }}</div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div class="flex items-center gap-2"><i class="far fa-calendar text-indigo-600"></i><span>Ngày:</span><span class="font-medium">{{ \App\Helpers\ServiceAppointmentHelper::formatDate($appointment->appointment_date) }}</span></div>
                <div class="flex items-center gap-2"><i class="far fa-clock text-indigo-600"></i><span>Giờ:</span><span class="font-medium">{{ \App\Helpers\ServiceAppointmentHelper::formatTime($appointment->appointment_time) }}</span></div>
                <div class="flex items-center gap-2"><i class="fas fa-store text-indigo-600"></i><span>Showroom:</span><span class="font-medium">{{ $appointment->showroom->name }}</span></div>
                <div class="flex items-center gap-2"><i class="fas fa-tag text-indigo-600"></i><span>Chi phí:</span><span class="font-medium">
                  @if($appointment->service->price > 0)
                    {{ number_format($appointment->service->price, 0, ',', '.') }} đ
                  @else
                    Miễn phí
                  @endif
                </span></div>
              </div>
            </div>
          </div>
          @else
          <div class="flex items-start gap-4 mb-6">
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
              <i class="fas fa-tools text-gray-600 text-lg"></i>
            </div>
            <div class="min-w-0 flex-1">
              <div class="text-gray-700 text-sm">Dịch vụ</div>
              <div class="font-semibold text-gray-900 mb-3">Chưa chọn dịch vụ</div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div class="flex items-center gap-2"><i class="far fa-calendar text-indigo-600"></i><span>Ngày:</span><span class="font-medium">{{ \App\Helpers\ServiceAppointmentHelper::formatDate($appointment->appointment_date) }}</span></div>
                <div class="flex items-center gap-2"><i class="far fa-clock text-indigo-600"></i><span>Giờ:</span><span class="font-medium">{{ \App\Helpers\ServiceAppointmentHelper::formatTime($appointment->appointment_time) }}</span></div>
                <div class="flex items-center gap-2"><i class="fas fa-store text-indigo-600"></i><span>Showroom:</span><span class="font-medium">{{ $appointment->showroom->name }}</span></div>
                <div class="flex items-center gap-2"><i class="fas fa-tag text-indigo-600"></i><span>Chi phí:</span><span class="font-medium">{{ $appointment->estimated_cost ? number_format($appointment->estimated_cost, 0, ',', '.') . ' đ' : '—' }}</span></div>
              </div>
            </div>
          </div>
          @endif

          <!-- Chi tiết bổ sung -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
            <div class="space-y-4">
              <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                <i class="fas fa-info-circle text-indigo-600"></i>
                Thông tin cơ bản
              </h4>
              <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Mã lịch:</dt><dd class="font-medium">{{ $appointment->appointment_number }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Trạng thái:</dt><dd><span class="px-2 py-0.5 rounded-full text-xs {{ \App\Helpers\ServiceAppointmentHelper::statusBadgeClass($appointment->status) }}">{{ \App\Helpers\ServiceAppointmentHelper::statusLabel($appointment->status) }}</span></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Ưu tiên:</dt><dd class="font-medium">{{ \App\Helpers\ServiceAppointmentHelper::priorityLabel($appointment->priority) }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Bảo hành:</dt><dd class="font-medium">{{ $appointment->is_warranty_work ? 'Có' : 'Không' }}</dd></div>
              </dl>
            </div>

            <div class="space-y-4">
              <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                <i class="fas fa-car text-indigo-600"></i>
                Thông tin xe
              </h4>
              <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Xe:</dt><dd class="font-medium">{{ $appointment->carVariant->carModel->carBrand->name }} {{ $appointment->carVariant->carModel->name }} {{ $appointment->carVariant->name }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Biển số:</dt><dd class="font-medium">{{ $appointment->vehicle_registration ?: '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Số km:</dt><dd class="font-medium">{{ $appointment->current_mileage ? number_format($appointment->current_mileage, 0, ',', '.') . ' km' : '—' }}</dd></div>
              </dl>
            </div>
          </div>

          <!-- Yêu cầu và mô tả -->
          @if($appointment->requested_services || $appointment->service_description)
            <div class="pt-6 border-t border-gray-100 mt-6">
              <h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-sticky-note text-indigo-600"></i>
                Yêu cầu & Mô tả
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($appointment->requested_services)
                  <div>
                    <div class="text-gray-700 text-sm mb-2 font-medium">Yêu cầu thêm</div>
                    <div class="text-sm text-gray-800 bg-gray-50 rounded-lg p-3 whitespace-pre-line">{{ $appointment->requested_services }}</div>
                  </div>
                @endif
                @if($appointment->service_description)
                  <div>
                    <div class="text-gray-700 text-sm mb-2 font-medium">Mô tả chi tiết</div>
                    <div class="text-sm text-gray-800 bg-gray-50 rounded-lg p-3 whitespace-pre-line">{{ $appointment->service_description }}</div>
                  </div>
                @endif
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="space-y-4 sm:space-y-6">
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Hành động</h2>
        @if($appointment->status === 'scheduled')
          <div class="flex flex-col gap-2">
            <a href="{{ route('user.service-appointments.edit', $appointment->id) }}" class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600 font-semibold text-sm"><i class="fas fa-edit"></i> Sửa</a>
            <button type="button" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700 font-semibold text-sm js-sa-cancel-one" data-cancel-url="{{ route('user.service-appointments.cancel', $appointment->id) }}"><i class="fas fa-times"></i> Hủy lịch</button>
          </div>
        @else
          <div class="text-sm text-gray-600">Lịch hẹn đã ở trạng thái {{ \App\Helpers\ServiceAppointmentHelper::statusLabel($appointment->status) }}.</div>
        @endif
      </div>

      @if($appointment->status === 'completed')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
          <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Đánh giá dịch vụ</h2>
          <form id="saRatingForm" class="space-y-3" data-submitting="0">
            @csrf
            @php($current = (int)round((float)($appointment->satisfaction_rating ?? 0)))
            <div class="flex items-center gap-2">
              <div class="flex items-center gap-1 select-none" id="saStars">
                @for($i=1;$i<=5;$i++)
                  <button type="button" data-val="{{ $i }}" class="sa-star inline-flex items-center justify-center h-9 w-9 text-2xl {{ $i <= $current ? 'text-amber-400' : 'text-gray-300' }}" aria-label="{{ $i }} sao">★</button>
                @endfor
                <input type="hidden" name="satisfaction_rating" id="saRatingVal" value="{{ $current ?: '' }}">
              </div>
              <span class="text-sm text-gray-600" id="saRatingCount">{{ $current ?: 0 }}/5</span>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Phản hồi</label>
              <textarea name="feedback" class="w-full border rounded-xl px-3 py-2" placeholder="Chia sẻ cảm nhận dịch vụ (tùy chọn)">{{ $appointment->feedback }}</textarea>
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg text-sm">Lưu đánh giá</button>
          </form>
        </div>
      @endif

      
      <script>
      (function(){
        const form = document.getElementById('saRatingForm');
        if (!form) return;
        const wrap = form.querySelector('#saStars');
        const val = form.querySelector('#saRatingVal');
        const countEl = form.querySelector('#saRatingCount');
        let current = parseInt(val.value||'0',10) || 0;
        function paint(n){
          [...wrap.querySelectorAll('.sa-star')].forEach((btn, idx)=>{
            btn.classList.toggle('text-amber-400', idx < n);
            btn.classList.toggle('text-gray-300', idx >= n);
          });
          if (countEl) countEl.textContent = String(n||0) + '/5';
        }
        paint(current);
        wrap.addEventListener('mouseover', (e)=>{ const b=e.target.closest('[data-val]'); if(!b) return; paint(parseInt(b.getAttribute('data-val'),10)); });
        wrap.addEventListener('mouseout', ()=>{ paint(current); });
        wrap.addEventListener('click', (e)=>{ const b=e.target.closest('[data-val]'); if(!b) return; current=parseInt(b.getAttribute('data-val'),10); val.value=String(current); paint(current); });

        form.addEventListener('submit', async function(ev){
          ev.preventDefault(); ev.stopPropagation(); if (form.dataset.submitting==='1') return; form.dataset.submitting='1';
          if (!val.value){ if (typeof showMessage==='function') showMessage('Vui lòng chọn số sao','warning'); form.dataset.submitting=''; return; }
          const fd = new FormData(form);
          try{
            const res = await fetch(`{{ route('user.service-appointments.rate', $appointment) }}`, { method:'POST', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json' }, body: fd });
            const data = await res.json().catch(()=>({}));
            if (res.ok && data.success){ if (typeof showMessage==='function') showMessage(data.message,'success'); }
            else { if (typeof showMessage==='function') showMessage(data.message || 'Không thể lưu đánh giá','error'); }
          } catch {} finally {
            form.dataset.submitting='';
          }
        });
      })();
      </script>
      <script>
      (function(){
        const cancelledBadgeClass = "{{ \App\Helpers\ServiceAppointmentHelper::statusBadgeClass('cancelled') }}";
        document.addEventListener('click', async function(e){
          const btn = e.target.closest('.js-sa-cancel-one');
          if (!btn) return;
          e.preventDefault();
          // modern dialog
          const confirmDialog = () => new Promise(resolve => {
            const existing = document.querySelector('.fast-confirm-dialog');
            if (existing) existing.remove();
            const wrapper = document.createElement('div');
            wrapper.className = 'fast-confirm-dialog fixed inset-0 z-[100000] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4';
            wrapper.innerHTML = `
              <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0">
                <div class="p-6">
                  <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4"><i class=\"fas fa-exclamation-triangle text-red-600 text-2xl\"></i></div>
                  <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Hủy lịch bảo dưỡng?</h3>
                  <p class="text-gray-600 text-center mb-6">Bạn có chắc chắn muốn hủy lịch này? Hành động không thể hoàn tác.</p>
                  <div class="flex space-x-3">
                    <button class="fast-cancel flex-1 px-4 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">Hủy bỏ</button>
                    <button class="fast-confirm flex-1 px-4 py-2.5 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium">Hủy lịch</button>
                  </div>
                </div>
              </div>`;
            document.body.appendChild(wrapper);
            const panel = wrapper.firstElementChild;
            requestAnimationFrame(()=>{ panel.style.transform='scale(1)'; panel.style.opacity='1'; });
            wrapper.addEventListener('click', (ev)=>{ if (ev.target === wrapper){ wrapper.remove(); resolve(false); } });
            wrapper.querySelector('.fast-cancel').addEventListener('click', ()=>{ wrapper.remove(); resolve(false); });
            wrapper.querySelector('.fast-confirm').addEventListener('click', ()=>{ wrapper.remove(); resolve(true); });
          });
          const ok = await confirmDialog();
          if (!ok) return;
          const originalHtml = btn.innerHTML;
          btn.disabled = true;
          btn.classList.remove('hover:bg-rose-700');
          btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang hủy...';
          const url = btn.getAttribute('data-cancel-url');
          try{
            const res = await fetch(url, { method:'PUT', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json' } });
            const data = await res.json().catch(()=>({}));
            if (res.ok && data && data.success){
              const topBadge = document.querySelector('[data-role="status-badge"]');
              if (topBadge){
                topBadge.className = 'px-2 py-0.5 rounded-full text-xs whitespace-nowrap inline-flex items-center ' + cancelledBadgeClass;
                topBadge.textContent = 'Đã hủy';
              }
              // Update status line in General Info
              (function(){
                const heading = Array.from(document.querySelectorAll('h2')).find(h=>h.textContent.trim()==='Thông tin chung');
                const card = heading ? heading.closest('.bg-white') : null;
                const rows = card ? card.querySelectorAll('.grid div') : [];
                rows.forEach(function(div){
                  const txt = (div.textContent||'').trim();
                  if (txt.startsWith('Trạng thái')){
                    const strong = div.querySelector('.font-semibold');
                    if (strong) strong.textContent = 'Đã hủy';
                  }
                });
              })();
              // Remove edit link and cancel button, then show state text in Actions card
              const actionsWrap = btn.closest('.flex.flex-col.gap-2') || btn.closest('.flex');
              if (actionsWrap){
                const editLink = actionsWrap.querySelector('a[href*="/service-appointments/"][href$="/edit"]');
                if (editLink) editLink.remove();
              }
              const card = btn.closest('.bg-white');
              if (card){
                const title = card.querySelector('h2');
                let node = title ? title.nextSibling : null;
                while (node){ const next = node.nextSibling; card.removeChild(node); node = next; }
                const msg = document.createElement('div');
                msg.className = 'text-sm text-gray-600';
                msg.textContent = 'Lịch hẹn đã ở trạng thái Đã hủy.';
                card.appendChild(msg);
              } else {
                btn.remove();
              }
              if (typeof window.showMessage === 'function') window.showMessage(data.message || 'Đã hủy lịch bảo dưỡng', 'success');
              if (window.refreshNotifBadge) window.refreshNotifBadge();
            } else {
              throw new Error((data && data.message) ? data.message : 'Hủy lịch thất bại');
            }
          } catch(err){
            if (typeof window.showMessage === 'function') window.showMessage(err.message || 'Hủy lịch thất bại', 'error');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            btn.classList.add('hover:bg-rose-700');
          }
        });
      })();
      </script>
    </div>
  </div>
</div>
@endsection


