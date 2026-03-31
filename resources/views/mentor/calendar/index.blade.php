@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('title', $isAr ? 'تقويم الإرشاد' : 'Mentorship calendar')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css">
@endpush

@section('content')
<div class="row row-cards mb-3">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">{{ $isAr ? 'إضافة أوقات التوفر' : 'Add availability' }}</div>
            <div class="card-body">
                <form method="post" action="{{ route('mentor.calendar.availability.store') }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">{{ $isAr ? 'يبدأ' : 'Starts' }}</label>
                        <input type="datetime-local" name="starts_at" class="form-control @error('starts_at') is-invalid @enderror" value="{{ old('starts_at') }}" required>
                        @error('starts_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">{{ $isAr ? 'ينتهي' : 'Ends' }}</label>
                        <input type="datetime-local" name="ends_at" class="form-control @error('ends_at') is-invalid @enderror" value="{{ old('ends_at') }}" required>
                        @error('ends_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ $isAr ? 'ملاحظة (اختياري)' : 'Note (optional)' }}</label>
                        <input type="text" name="note" class="form-control" value="{{ old('note') }}" maxlength="500" placeholder="{{ $isAr ? 'مثال: جلسات افتراضية' : 'e.g. Video calls' }}">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">{{ $isAr ? 'حفظ التوفر' : 'Save availability' }}</button>
                </form>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">{{ $isAr ? 'فترات التوفر المحفوظة' : 'Saved availability' }}</div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>{{ $isAr ? 'من' : 'From' }}</th>
                            <th>{{ $isAr ? 'إلى' : 'To' }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($slots as $slot)
                        <tr>
                            <td>{{ $slot->starts_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $slot->ends_at->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <form method="post" action="{{ route('mentor.calendar.availability.destroy', $slot) }}" onsubmit="return confirm('{{ $isAr ? 'حذف هذه الفترة؟' : 'Remove this slot?' }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">{{ $isAr ? 'حذف' : 'Remove' }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">{{ $isAr ? 'لا توجد فترات بعد.' : 'No availability yet.' }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-2">{{ $slots->links() }}</div>
    </div>
    <div class="col-lg-7">
        <div class="card mb-3">
            <div class="card-body p-2">
                <div id="mentor-calendar" class="p-2"></div>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-3 small text-muted mb-2">
            <span><span class="badge" style="background:#2fb344">&nbsp;</span> {{ $isAr ? 'توفر' : 'Availability' }}</span>
            <span><span class="badge" style="background:#206bc4">&nbsp;</span> {{ $isAr ? 'اجتماع' : 'Meeting' }}</span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">{{ $isAr ? 'المواعيد القادمة (طلبات)' : 'Upcoming meeting requests' }}</div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>{{ $isAr ? 'التاريخ' : 'Date' }}</th>
                    <th>{{ $isAr ? 'المشروع' : 'Project' }}</th>
                    <th>{{ $isAr ? 'رائد الأعمال' : 'Entrepreneur' }}</th>
                    <th>{{ $isAr ? 'الحالة' : 'Status' }}</th>
                </tr>
            </thead>
            <tbody>
            @forelse($requests as $meeting)
                <tr>
                    <td>{{ optional($meeting->requested_for)->format('Y-m-d H:i') }}</td>
                    <td>{{ optional($meeting->project)->title }}</td>
                    <td>{{ optional($meeting->entrepreneur)->name }}</td>
                    <td><x-status-badge :status="$meeting->status" /></td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">{{ $isAr ? 'لا توجد طلبات.' : 'No meetings.' }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body py-2">{{ $requests->links() }}</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales-all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('mentor-calendar');
    if (!el || typeof FullCalendar === 'undefined') return;
    var calendar = new FullCalendar.Calendar(el, {
        initialView: 'dayGridMonth',
        locale: '{{ app()->getLocale() }}',
        direction: '{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today: '{{ $isAr ? 'اليوم' : 'Today' }}',
            month: '{{ $isAr ? 'شهر' : 'Month' }}',
            week: '{{ $isAr ? 'أسبوع' : 'Week' }}',
            list: '{{ $isAr ? 'قائمة' : 'List' }}'
        },
        height: 'auto',
        events: '{{ route('mentor.calendar.events') }}',
        eventDisplay: 'block',
        displayEventTime: true
    });
    calendar.render();
});
</script>
@endpush
