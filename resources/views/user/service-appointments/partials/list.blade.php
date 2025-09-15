@if($appointments->count() > 0)
    <div class="space-y-3 sm:space-y-4">
        @foreach($appointments as $appointment)
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="p-3 sm:p-5 flex flex-col h-full">
                        <div class="flex-1 flex flex-col gap-3 sm:gap-4">
                            <div class="flex items-start sm:items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <a href="{{ route('user.service-appointments.show', $appointment->id) }}" class="text-gray-800 font-semibold truncate hover:underline">#{{ $appointment->appointment_number }}</a>
                                        <span class="hidden xs:inline text-gray-400">•</span>
                                        <span class="text-gray-500 text-xs sm:text-sm">{{ optional($appointment->appointment_date)->format('d/m/Y') }} {{ $appointment->appointment_time }}</span>
                                    </div>
                                    <div class="text-xs sm:text-sm text-gray-500 mt-1 truncate">{{ $appointment->carVariant->carModel->carBrand->name }} {{ $appointment->carVariant->carModel->name }} • {{ \App\Helpers\ServiceAppointmentHelper::typeLabel($appointment->appointment_type) }}</div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="px-2 py-0.5 rounded-full text-xs whitespace-nowrap inline-flex items-center {{ \App\Helpers\ServiceAppointmentHelper::statusBadgeClass($appointment->status) }}" data-role="status-badge">{{ \App\Helpers\ServiceAppointmentHelper::statusLabel($appointment->status) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-[11px] sm:text-xs text-gray-500">
                                <span>Showroom: <span class="font-medium text-gray-700">{{ $appointment->showroom->name }}</span></span>
                                <span class="hidden xs:inline">•</span>
                                <span>Ưu tiên: <span class="font-medium text-gray-700">{{ \App\Helpers\ServiceAppointmentHelper::priorityLabel($appointment->priority) }}</span></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('user.service-appointments.show', $appointment->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs"><i class="fas fa-eye"></i> Chi tiết</a>
                                <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-600 text-white hover:bg-rose-700 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed js-sa-cancel-one"
                                  data-cancel-url="{{ route('user.service-appointments.cancel', $appointment->id) }}" {{ $appointment->status === 'scheduled' ? '' : 'disabled' }}>
                                    <i class="fas fa-ban"></i> Hủy lịch
                                </button>
                            </div>
                        </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 sm:p-14 text-center">
        <div class="w-16 h-16 mx-auto mb-5 bg-gray-100 rounded-full flex items-center justify-center">
            <i class="fas fa-tools text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">Chưa có lịch bảo dưỡng</h3>
        <p class="text-sm sm:text-base text-gray-500 max-w-xl mx-auto">Bạn chưa đặt lịch bảo dưỡng nào. Hãy đặt lịch đầu tiên để chúng tôi phục vụ bạn tốt hơn.</p>
        
    </div>
@endif

<script>
(function(){
  const cancelledBadgeClass = "{{ \App\Helpers\ServiceAppointmentHelper::statusBadgeClass('cancelled') }}";
  document.addEventListener('click', async function(e){
    const btn = e.target.closest('.js-sa-cancel-one');
    if (!btn) return;
    e.preventDefault();
    const rootCard = btn.closest('.bg-white');
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
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang hủy...';
    try{
      const url = btn.getAttribute('data-cancel-url');
      const res = await fetch(url, { method:'PUT', headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json' } });
      const data = await res.json().catch(()=>({}));
      if (res.ok && data && data.success){
        // disable button, update badge immediately
        btn.classList.remove('hover:bg-rose-700');
        const badge = rootCard ? rootCard.querySelector('[data-role="status-badge"]') : null;
        if (badge){ badge.className = 'px-2 py-0.5 rounded-full text-xs whitespace-nowrap inline-flex items-center ' + cancelledBadgeClass; badge.textContent='Đã hủy'; }
        // finalize button state
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-ban"></i> Đã hủy';
        if (typeof window.showMessage === 'function') window.showMessage(data.message || 'Đã hủy lịch bảo dưỡng', 'success');
      } else {
        throw new Error((data && data.message) ? data.message : 'Hủy lịch thất bại');
      }
    } catch(err){
      if (typeof window.showMessage === 'function') window.showMessage(err.message || 'Hủy lịch thất bại', 'error');
      btn.disabled = false; btn.innerHTML = originalHtml;
    }
  });
})();
</script>


