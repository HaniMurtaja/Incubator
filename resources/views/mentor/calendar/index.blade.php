@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('title', $isAr ? 'تقويم الإرشاد' : 'Mentorship calendar')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css">
@endpush

@section('content')
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'availability' ? 'active' : '' }}" href="{{ route('mentor.calendar.index', ['tab' => 'availability']) }}">
            {{ $isAr ? 'أيام التوفر' : 'Availability' }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'create-meeting' ? 'active' : '' }}" href="{{ route('mentor.calendar.index', ['tab' => 'create-meeting']) }}">
            {{ $isAr ? 'إنشاء اجتماع' : 'Create Meeting' }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'upcoming' ? 'active' : '' }}" href="{{ route('mentor.calendar.index', ['tab' => 'upcoming']) }}">
            {{ $isAr ? 'الاجتماعات القادمة' : 'Upcoming Meetings' }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'requests' ? 'active' : '' }}" href="{{ route('mentor.calendar.index', ['tab' => 'requests']) }}">
            {{ $isAr ? 'طلبات الاجتماعات' : 'Meeting Requests' }}
        </a>
    </li>
</ul>

@if($tab === 'availability')
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
@endif

@if($tab === 'create-meeting')
<div class="card mb-3">
    <div class="card-header">{{ $isAr ? 'إنشاء اجتماع جديد' : 'Create new meeting' }}</div>
    <div class="card-body">
        <form method="post" action="{{ route('mentor.calendar.meetings.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">{{ $isAr ? 'المشروع' : 'Project' }}</label>
                <select name="project_id" class="form-select @error('project_id') is-invalid @enderror" required>
                    <option value="">{{ $isAr ? 'اختر المشروع' : 'Select project' }}</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" @if(old('project_id') == $project->id) selected @endif>{{ $project->title }}</option>
                    @endforeach
                </select>
                @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ $isAr ? 'تاريخ ووقت الاجتماع' : 'Meeting date and time' }}</label>
                <input type="datetime-local" name="requested_for" class="form-control @error('requested_for') is-invalid @enderror" value="{{ old('requested_for') }}" required>
                @error('requested_for')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ $isAr ? 'نوع الاجتماع' : 'Mode' }}</label>
                <select name="meeting_mode" class="form-select @error('meeting_mode') is-invalid @enderror" required>
                    <option value="online" @if(old('meeting_mode', 'online') === 'online') selected @endif>{{ $isAr ? 'عن بعد' : 'Online' }}</option>
                    <option value="offline" @if(old('meeting_mode') === 'offline') selected @endif>{{ $isAr ? 'حضوري' : 'Offline' }}</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ $isAr ? 'مدة الاجتماع' : 'Duration' }}</label>
                <select name="duration_minutes" class="form-select @error('duration_minutes') is-invalid @enderror" required>
                    @foreach([15,30,45,60,75,90] as $duration)
                        <option value="{{ $duration }}" @if((int) old('duration_minutes', 30) === $duration) selected @endif>{{ $duration }} {{ $isAr ? 'دقيقة' : 'min' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <label class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="notify_members" value="1" @if(old('notify_members', '1')) checked @endif>
                    <span class="form-check-label">{{ $isAr ? 'إشعار الأعضاء' : 'Notify members' }}</span>
                </label>
            </div>
            <div class="col-12">
                <label class="form-label">{{ $isAr ? 'ملاحظات الاجتماع' : 'Agenda' }}</label>
                <textarea class="form-control" name="agenda" rows="3" placeholder="{{ $isAr ? 'أضف هدف الاجتماع' : 'Add meeting agenda' }}">{{ old('agenda') }}</textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ $isAr ? 'إنشاء الاجتماع' : 'Create meeting' }}</button>
            </div>
        </form>
    </div>
</div>
@endif

@if($tab === 'upcoming')
<div class="card">
    <div class="card-header">{{ $isAr ? 'الاجتماعات القادمة مع الأعضاء' : 'Upcoming meetings with members' }}</div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>{{ $isAr ? 'التاريخ' : 'Date' }}</th>
                    <th>{{ $isAr ? 'المشروع' : 'Project' }}</th>
                    <th>{{ $isAr ? 'رائد الأعمال' : 'Entrepreneur' }}</th>
                    <th>{{ $isAr ? 'النوع' : 'Mode' }}</th>
                    <th>{{ $isAr ? 'المدة' : 'Duration' }}</th>
                    <th>{{ $isAr ? 'الحالة' : 'Status' }}</th>
                </tr>
            </thead>
            <tbody>
            @forelse($requests as $meeting)
                <tr>
                    <td>{{ optional($meeting->requested_for)->format('Y-m-d H:i') }}</td>
                    <td>{{ optional($meeting->project)->title }}</td>
                    <td>{{ optional($meeting->entrepreneur)->name }}</td>
                    <td>{{ $meeting->meeting_mode === 'offline' ? ($isAr ? 'حضوري' : 'Offline') : ($isAr ? 'عن بعد' : 'Online') }}</td>
                    <td>{{ $meeting->duration_minutes }} {{ $isAr ? 'دقيقة' : 'min' }}</td>
                    <td><x-status-badge :status="$meeting->status" /></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">{{ $isAr ? 'لا توجد اجتماعات.' : 'No meetings.' }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body py-2">{{ $requests->links() }}</div>
</div>
@endif

@if($tab === 'requests')
<div class="card">
    <div class="card-header">{{ $isAr ? 'طلبات الاجتماعات بانتظار الموافقة' : 'Meeting requests pending approval' }}</div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>{{ $isAr ? 'التاريخ' : 'Date' }}</th>
                    <th>{{ $isAr ? 'المشروع' : 'Project' }}</th>
                    <th>{{ $isAr ? 'رائد الأعمال' : 'Entrepreneur' }}</th>
                    <th>{{ $isAr ? 'النوع' : 'Mode' }}</th>
                    <th>{{ $isAr ? 'المدة' : 'Duration' }}</th>
                    <th>{{ $isAr ? 'الإجراء' : 'Action' }}</th>
                </tr>
            </thead>
            <tbody>
            @forelse($meetingRequests as $meeting)
                <tr>
                    <td>{{ optional($meeting->requested_for)->format('Y-m-d H:i') }}</td>
                    <td>{{ optional($meeting->project)->title ?? '-' }}</td>
                    <td>{{ optional($meeting->entrepreneur)->name ?? '-' }}</td>
                    <td>{{ $meeting->meeting_mode === 'offline' ? ($isAr ? 'حضوري' : 'Offline') : ($isAr ? 'عن بعد' : 'Online') }}</td>
                    <td>{{ $meeting->duration_minutes }} {{ $isAr ? 'دقيقة' : 'min' }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <form method="post" action="{{ route('mentor.calendar.requests.status.update', $meeting) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button class="btn btn-sm btn-success">{{ $isAr ? 'قبول' : 'Approve' }}</button>
                            </form>
                            <form method="post" action="{{ route('mentor.calendar.requests.status.update', $meeting) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button class="btn btn-sm btn-outline-danger">{{ $isAr ? 'رفض' : 'Reject' }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">{{ $isAr ? 'لا توجد طلبات حالياً.' : 'No pending requests.' }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body py-2">{{ $meetingRequests->links() }}</div>
</div>
@endif
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
