<h2>Xác nhận lịch hẹn dịch vụ</h2>

<p>Xin chào {{ $appointment->customer_name }},</p>

<p>Lịch hẹn dịch vụ của bạn đã được ghi nhận:</p>

<ul>
  <li>Mã lịch hẹn: <strong>{{ $appointment->appointment_number }}</strong></li>
  <li>Ngày: <strong>{{ $appointment->appointment_date }}</strong></li>
  <li>Giờ: <strong>{{ $appointment->appointment_time }}</strong></li>
  <li>Loại dịch vụ: <strong>{{ $appointment->appointment_type }}</strong></li>
  <li>Trạng thái: <strong>{{ $appointment->status }}</strong></li>
  @if(!empty($appointment->service_description))
  <li>Mô tả: {{ $appointment->service_description }}</li>
  @endif
  @if($appointment->carVariant)
  <li>Xe: {{ optional($appointment->carVariant->carModel->carBrand)->name }} - {{ optional($appointment->carVariant->carModel)->name }} ({{ $appointment->carVariant->name }})</li>
  @endif
  @if($appointment->showroom)
  <li>Showroom: {{ $appointment->showroom->name }}</li>
  @endif
  
</ul>

<p>Chúng tôi sẽ liên hệ để xác nhận và hỗ trợ bạn sớm nhất.</p>

<p>Trân trọng,<br/>Showroom Car</p>


