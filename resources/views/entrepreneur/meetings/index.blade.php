@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('title', $isAr ? 'اجتماعاتي' : 'My Meetings')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css">
@endpush

@section('content')
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'upcoming' ? 'active' : '' }}" href="{{ route('entrepreneur.meetings.index', ['tab' => 'upcoming']) }}">
            {{ $isAr ? 'الاجتماعات' : 'Meetings' }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'request' ? 'active' : '' }}" href="{{ route('entrepreneur.meetings.index', ['tab' => 'request']) }}">
            {{ $isAr ? 'طلب اجتماع جديد' : 'Request New Meeting' }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'requests' ? 'active' : '' }}" href="{{ route('entrepreneur.meetings.index', ['tab' => 'requests']) }}">
            {{ $isAr ? 'الطلبات المرسلة' : 'Sent Requests' }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'availability' ? 'active' : '' }}" href="{{ route('entrepreneur.meetings.index', ['tab' => 'availability']) }}">
            {{ $isAr ? 'أوقات توفر الموجهين' : 'Mentor Availability' }}
        </a>
    </li>
</ul>

@if($tab === 'request')
<div class="card mb-3">
    <div class="card-header">{{ $isAr ? 'إرسال طلب اجتماع للموجه' : 'Send meeting request to mentor' }}</div>
    <div class="card-body">
        <form method="post" action="{{ route('entrepreneur.meetings.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">{{ $isAr ? 'المشروع' : 'Project' }}</label>
                <select name="project_id" class="form-select">
                    <option value="">{{ $isAr ? 'اختياري' : 'Optional' }}</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" @if(old('project_id') == $project->id) selected @endif>{{ $project->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ $isAr ? 'الجولة' : 'Round' }}</label>
                <select name="incubator_round_id" class="form-select">
                    <option value="">{{ $isAr ? 'اختياري' : 'Optional' }}</option>
                    @foreach($rounds as $round)
                        <option value="{{ $round->id }}" @if(old('incubator_round_id') == $round->id) selected @endif>{{ $round->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ $isAr ? 'الموجه' : 'Mentor' }}</label>
                <select name="mentor_id" class="form-select @error('mentor_id') is-invalid @enderror" required>
                    <option value="">{{ $isAr ? 'اختر' : 'Select' }}</option>
                    @foreach($mentors as $mentor)
                        <option value="{{ $mentor->id }}" @if(old('mentor_id') == $mentor->id) selected @endif>{{ $mentor->name }}</option>
                    @endforeach
                </select>
                @error('mentor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ $isAr ? 'التاريخ والوقت' : 'Date and time' }}</label>
                <input type="datetime-local" class="form-control @error('requested_for') is-invalid @enderror" name="requested_for" value="{{ old('requested_for') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ $isAr ? 'نوع الاجتماع' : 'Mode' }}</label>
                <select name="meeting_mode" class="form-select" required>
                    <option value="online" @if(old('meeting_mode', 'online') === 'online') selected @endif>{{ $isAr ? 'عن بعد' : 'Online' }}</option>
                    <option value="offline" @if(old('meeting_mode') === 'offline') selected @endif>{{ $isAr ? 'حضوري' : 'Offline' }}</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ $isAr ? 'المدة' : 'Duration' }}</label>
                <select name="duration_minutes" class="form-select" required>
                    @foreach([15,30,45,60,75,90] as $duration)
                        <option value="{{ $duration }}" @if((int) old('duration_minutes', 30) === $duration) selected @endif>{{ $duration }} {{ $isAr ? 'دقيقة' : 'min' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <label class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="notify_members" value="1" @if(old('notify_members', '1')) checked @endif>
                    <span class="form-check-label">{{ $isAr ? 'إشعار الأطراف' : 'Notify members' }}</span>
                </label>
            </div>
            <div class="col-12">
                <label class="form-label">{{ $isAr ? 'أجندة الاجتماع' : 'Agenda' }}</label>
                <textarea class="form-control" rows="3" name="agenda">{{ old('agenda') }}</textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-primary">{{ $isAr ? 'إرسال الطلب' : 'Send request' }}</button>
            </div>
        </form>
    </div>
</div>
@endif

@if($tab === 'upcoming' || $tab === 'requests')
<div class="card">
    <div class="card-header">
        {{ $tab === 'requests'
            ? ($isAr ? 'طلبات الاجتماعات المرسلة' : 'Sent meeting requests')
            : ($isAr ? 'الاجتماعات القادمة مع الموجهين' : 'Upcoming meetings with mentors') }}
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>{{ $isAr ? 'التاريخ' : 'Date' }}</th>
                    <th>{{ $isAr ? 'الجولة' : 'Round' }}</th>
                    <th>{{ $isAr ? 'المشروع' : 'Project' }}</th>
                    <th>{{ $isAr ? 'الموجه' : 'Mentor' }}</th>
                    <th>{{ $isAr ? 'النوع' : 'Mode' }}</th>
                    <th>{{ $isAr ? 'المدة' : 'Duration' }}</th>
                    <th>{{ $isAr ? 'الحالة' : 'Status' }}</th>
                </tr>
            </thead>
            <tbody>
            @forelse($meetings as $meeting)
                <tr>
                    <td>{{ optional($meeting->requested_for)->format('Y-m-d H:i') }}</td>
                    <td>{{ optional($meeting->round)->name ?? '-' }}</td>
                    <td>{{ optional($meeting->project)->title ?? '-' }}</td>
                    <td>{{ optional($meeting->mentor)->name ?? '-' }}</td>
                    <td>{{ $meeting->meeting_mode === 'offline' ? ($isAr ? 'حضوري' : 'Offline') : ($isAr ? 'عن بعد' : 'Online') }}</td>
                    <td>{{ $meeting->duration_minutes }} {{ $isAr ? 'دقيقة' : 'min' }}</td>
                    <td><x-status-badge :status="$meeting->status" /></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">{{ $isAr ? 'لا توجد اجتماعات بعد.' : 'No meetings yet.' }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body py-2">{{ $meetings->links() }}</div>
</div>
@endif
@if($tab === 'availability')
<div class="card">
    <div class="card-header">{{ $isAr ? 'تقويم توفر الموجهين' : 'Mentor availability calendar' }}</div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label">{{ $isAr ? 'تصفية حسب الموجه' : 'Filter by mentor' }}</label>
                <select id="mentor-filter" class="form-select">
                    <option value="">{{ $isAr ? 'كل الموجهين' : 'All mentors' }}</option>
                    @foreach($mentors as $mentor)
                        <option value="{{ $mentor->id }}">{{ $mentor->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="mentor-availability-calendar"></div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales-all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarElement = document.getElementById('mentor-availability-calendar');
    if (!calendarElement || typeof FullCalendar === 'undefined') return;

    var mentorFilter = document.getElementById('mentor-filter');
    var eventsUrl = '{{ route('entrepreneur.meetings.availability.events') }}';

    var calendar = new FullCalendar.Calendar(calendarElement, {
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
        events: function(fetchInfo, successCallback, failureCallback) {
            var url = eventsUrl;
            if (mentorFilter && mentorFilter.value) {
                url += '?mentor_id=' + encodeURIComponent(mentorFilter.value);
            }
            fetch(url)
                .then(function(response) { return response.json(); })
                .then(successCallback)
                .catch(failureCallback);
        },
        eventDisplay: 'block',
        displayEventTime: true
    });

    calendar.render();

    if (mentorFilter) {
        mentorFilter.addEventListener('change', function () {
            calendar.refetchEvents();
        });
    }
});
</script>
@endpush

