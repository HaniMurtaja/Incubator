@extends('layouts.app')

@php
    $isAr = app()->getLocale() === 'ar';
@endphp

@section('title', $isAr ? 'طلبات الاجتماعات' : 'Meeting Requests')

@push('styles')
@if ($tab === 'calendar')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css">
@endif
@endpush

@section('content')

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'requests' ? 'active' : '' }}"
           href="{{ route('admin.meetings.index', array_merge(request()->except('tab'), ['tab' => 'requests'])) }}">
            {{ $isAr ? 'طلبات الاجتماعات' : 'Meeting requests' }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'calendar' ? 'active' : '' }}"
           href="{{ route('admin.meetings.index', array_merge(request()->except('tab'), ['tab' => 'calendar'])) }}">
            {{ $isAr ? 'التقويم — الاجتماعات القادمة' : 'Calendar — upcoming' }}
        </a>
    </li>
</ul>

@if($tab === 'requests')

<p class="text-muted small mb-2">{{ $isAr ? 'عند اختيار مشروع يتم اقتراح الموجه ورائد الأعمال المرتبطين بالمشروع.' : 'Selecting a project suggests its linked mentor & entrepreneur.' }}</p>

<x-filter-bar>
    <input type="hidden" name="tab" value="requests">
    <div class="col-md-3">
        <label class="form-label">{{ $isAr ? 'المشروع' : 'Project' }}</label>
        <select class="form-select" name="project_id" id="meetingsFilterProject">
            <option value="">{{ $isAr ? 'كل المشاريع' : 'All projects' }}</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}"
                    data-mentor-id="{{ $project->mentor_id }}"
                    data-entrepreneur-id="{{ $project->entrepreneur_id }}"
                    @if(($filters['project_id'] ?? '') == $project->id) selected @endif>{{ $project->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">{{ $isAr ? 'الموجه' : 'Mentor' }}</label>
        <select class="form-select" name="mentor_id" id="meetingsFilterMentor">
            <option value="">{{ $isAr ? 'كل الموجهين' : 'All mentors' }}</option>
            @foreach($mentors as $mentor)
                <option value="{{ $mentor->id }}" @if(($filters['mentor_id'] ?? '') == $mentor->id) selected @endif>{{ $mentor->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">{{ $isAr ? 'رائد الأعمال' : 'Entrepreneur' }}</label>
        <select class="form-select" name="entrepreneur_id" id="meetingsFilterEntrepreneur">
            <option value="">{{ $isAr ? 'الكل' : 'All' }}</option>
            @foreach($entrepreneurs as $entrepreneur)
                <option value="{{ $entrepreneur->id }}" @if(($filters['entrepreneur_id'] ?? '') == $entrepreneur->id) selected @endif>{{ $entrepreneur->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label">{{ $isAr ? 'حالة الاجتماع' : 'Meeting status' }}</label>
        <select class="form-select" name="status">
            <option value="">{{ $isAr ? 'كل الحالات' : 'All statuses' }}</option>
            @foreach($statuses as $st)
                <option value="{{ $st }}" @if(($filters['status'] ?? '') === $st) selected @endif>{{ str_replace('_', ' ', $st) }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>

@if($hasActiveFilter)
<div class="card mb-3">
    <div class="card-header">{{ $isAr ? 'اجتماعات حسب المشروع' : 'Meetings by project' }}</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ $isAr ? 'المشروع' : 'Project' }}</th>
                        <th>{{ $isAr ? 'إجمالي الاجتماعات' : 'Total' }}</th>
                        <th>{{ $isAr ? 'منجز' : 'Done' }}</th>
                        <th>{{ $isAr ? 'قيد التنفيذ' : 'In progress' }}</th>
                        <th>{{ $isAr ? 'مؤجل' : 'Postponed' }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($projectStats as $proj)
                    <tr>
                        <td>{{ $proj->title }}</td>
                        <td>{{ $proj->meetings_total }}</td>
                        <td>{{ $proj->meetings_done }}</td>
                        <td>{{ $proj->meetings_in_progress }}</td>
                        <td>{{ $proj->meetings_postponed }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">{{ $isAr ? 'لا مشاريع بعد.' : 'No projects yet.' }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if(!$hasActiveFilter)
    <div class="alert alert-info mb-0">
        {{ $isAr ? 'اختر مشروعاً أو موجهاً أو رائد أعمال أو حالة اجتماع، ثم اضغط «تصفية» لعرض ملخص الاجتماعات حسب المشروع وجدول الطلبات.' : 'Pick at least one filter (project, mentor, entrepreneur, or status) and click Filter to show meetings-by-project summary and the requests table.' }}
    </div>
@else
<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>{{ $isAr ? 'التاريخ' : 'Date' }}</th><th>{{ $isAr ? 'المشروع' : 'Project' }}</th><th>{{ $isAr ? 'الموجه' : 'Mentor' }}</th><th>{{ $isAr ? 'رائد الأعمال' : 'Entrepreneur' }}</th><th>{{ $isAr ? 'الحالة' : 'Status' }}</th><th>{{ $isAr ? 'إجراءات' : 'Actions' }}</th></tr></thead>
            <tbody>
            @forelse($requests as $meeting)
                <tr>
                    <td>{{ optional($meeting->requested_for)->format('Y-m-d H:i') }}</td>
                    <td>{{ optional($meeting->project)->title ?: '—' }}</td>
                    <td>{{ optional($meeting->mentor)->name }}</td>
                    <td>{{ optional($meeting->entrepreneur)->name }}</td>
                    <td><x-status-badge :status="$meeting->status" /></td>
                    <td class="d-flex gap-2 flex-wrap">
                        <form method="post" action="{{ route('admin.meetings.update', $meeting->id) }}" class="d-flex gap-2 flex-wrap align-items-center">
                            @csrf @method('put')
                            <input type="hidden" name="requested_for" value="{{ optional($meeting->requested_for)->format('Y-m-d H:i:s') }}">
                            <input type="hidden" name="agenda" value="{{ $meeting->agenda ?? '' }}">
                            <select class="form-select form-select-sm" name="status" style="min-width:9rem;">
                                @foreach($statuses as $st)
                                    <option value="{{ $st }}" @if($meeting->status === $st) selected @endif>{{ str_replace('_', ' ', $st) }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-outline-primary">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                        </form>
                        <form method="post" action="{{ route('admin.meetings.destroy', $meeting->id) }}" onsubmit="return confirm('{{ $isAr ? 'حذف؟' : 'Delete?' }}');">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">{{ $isAr ? 'حذف' : 'Delete' }}</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">{{ $isAr ? 'لا نتائج لهذه التصفية.' : 'No meetings match these filters.' }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $requests->links() }}</div>
@endif

@push('scripts')
<script>
(function () {
    var proj = document.getElementById('meetingsFilterProject');
    var ment = document.getElementById('meetingsFilterMentor');
    var entr = document.getElementById('meetingsFilterEntrepreneur');
    if (!proj || !ment || !entr) return;
    proj.addEventListener('change', function () {
        var opt = proj.options[proj.selectedIndex];
        if (!opt || !opt.value) return;
        var mid = opt.getAttribute('data-mentor-id');
        var eid = opt.getAttribute('data-entrepreneur-id');
        if (mid) {
            for (var i = 0; i < ment.options.length; i++) {
                if (ment.options[i].value === String(mid)) { ment.selectedIndex = i; break; }
            }
        }
        if (eid) {
            for (var j = 0; j < entr.options.length; j++) {
                if (entr.options[j].value === String(eid)) { entr.selectedIndex = j; break; }
            }
        }
    });
})();
</script>
@endpush

@else

<div class="card mb-3">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-6">
            <label class="form-label">{{ $isAr ? 'تصفية حسب المشروع' : 'Filter by project' }}</label>
            <select class="form-select" id="adminCalProjectFilter">
                <option value="">{{ $isAr ? 'كل المشاريع' : 'All projects' }}</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <button type="button" class="btn btn-primary" id="adminCalReload">{{ $isAr ? 'تحديث التقويم' : 'Reload calendar' }}</button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-2">
        <div id="admin-meetings-calendar" class="p-2"></div>
    </div>
</div>

@endif
@endsection

@if ($tab === 'calendar')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales-all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('admin-meetings-calendar');
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
        events: function (info, successCallback, failureCallback) {
            var url = '{{ route('admin.meetings.calendar.events') }}';
            var pid = document.getElementById('adminCalProjectFilter') && document.getElementById('adminCalProjectFilter').value;
            if (pid) {
                url += (url.indexOf('?') >= 0 ? '&' : '?') + 'project_id=' + encodeURIComponent(pid);
            }
            fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }})
                .then(function (r) { return r.json(); })
                .then(successCallback)
                .catch(failureCallback);
        },
        eventDisplay: 'block',
        displayEventTime: true
    });
    calendar.render();

    var reloadBtn = document.getElementById('adminCalReload');
    var projSel = document.getElementById('adminCalProjectFilter');
    if (reloadBtn) {
        reloadBtn.addEventListener('click', function () { calendar.refetchEvents(); });
    }
    if (projSel) {
        projSel.addEventListener('change', function () { calendar.refetchEvents(); });
    }
});
</script>
@endpush
@endif
