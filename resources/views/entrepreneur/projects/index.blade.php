@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'مشاريعي' : 'My Projects')

@push('styles')
<style>
.ep-page { font-size: 14.5px; }
.ep-page .form-label, .ep-page label { font-size: 13px; font-weight: 600; color: #4A5568; }
.ep-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 1.125rem;
}
.ep-toolbar-title {
    font-size: 18px;
    font-weight: 700;
    color: #0F1724;
    margin: 0;
}
.ep-page .card {
    background: #fff;
    border: 1px solid #DDE2EC;
    border-radius: 12px;
    box-shadow: none;
}
.ep-page .card-body { padding: 1.125rem 1.5rem; }
.ep-page .form-control, .ep-page .form-select {
    border-radius: 9px;
    border: 1.5px solid #DDE2EC;
    background: #F8F9FB;
    font-size: 14px;
}
.ep-page .form-control:focus, .ep-page .form-select:focus {
    border-color: #1A56DB;
    background: #fff;
}
.ep-primary-btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    border: 1.5px solid #BFCFEF;
    background: #EBF2FF;
    color: #1A56DB;
    transition: background .15s, color .15s, border-color .15s, transform .12s;
}
.ep-primary-btn:hover {
    background: #1A56DB;
    color: #fff;
    border-color: #1A56DB;
    transform: translateY(-1px);
    text-decoration: none;
}
.ep-page .btn-primary {
    background: #1A56DB;
    border-color: #1A56DB;
    border-radius: 8px;
    font-weight: 700;
    font-size: 13px;
    padding: 9px 18px;
}
.ep-page .btn-outline-primary {
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
}
.ep-page .btn-outline-secondary {
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
}
.cc-table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid #DDE2EC; }
.cc-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    margin-bottom: 0;
}
.cc-table thead th {
    background: #F1F3F7;
    padding: 11px 14px;
    font-size: 12px;
    font-weight: 700;
    color: #4A5568;
    text-transform: uppercase;
    letter-spacing: .5px;
    border-bottom: 1.5px solid #DDE2EC;
    white-space: nowrap;
}
.cc-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #EEF3F7;
    color: #0F1724;
    font-size: 14px;
    vertical-align: middle;
}
.cc-table tbody tr:last-child td { border-bottom: none; }
.cc-table tbody tr:hover td { background: #F8F9FB; }
.ep-progress { height: 8px; border-radius: 6px; background: #EEF3F7; overflow: hidden; }
.ep-progress .progress-bar { border-radius: 6px; background: #1A56DB; font-size: 11px; line-height: 8px; }
</style>
@endpush

@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp

<div class="ep-page">
    <div class="ep-toolbar">
        <h1 class="ep-toolbar-title">{{ $isAr ? 'مشاريعي' : 'My Projects' }}</h1>
        <a class="ep-primary-btn" href="{{ route('entrepreneur.projects.create') }}">
            {{ $isAr ? 'تقديم مشروع جديد' : 'Submit New Project' }}
        </a>
    </div>

    <x-filter-bar>
        <div class="col-md-4">
            <label class="form-label">{{ $isAr ? 'بحث' : 'Search' }}</label>
            <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ $isAr ? 'العنوان أو الوصف' : 'Title or description' }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ $isAr ? 'الحالة' : 'Status' }}</label>
            <select class="form-select" name="status">
                <option value="">{{ $isAr ? 'الكل' : 'All' }}</option>
                @foreach(['pending','accepted','rejected','in_progress','completed'] as $status)
                    <option value="{{ $status }}" @if(($filters['status'] ?? '') === $status) selected @endif>{{ str_replace('_',' ',$status) }}</option>
                @endforeach
            </select>
        </div>
    </x-filter-bar>

    @if($projects->isEmpty())
        <x-empty-state :title="$isAr ? 'لا توجد مشاريع.' : 'No projects found.'" />
    @else
        <div class="cc-table-wrap">
            <table class="table cc-table mb-0">
                <thead>
                    <tr>
                        <th>{{ $isAr ? 'العنوان' : 'Title' }}</th>
                        <th>{{ $isAr ? 'الحالة' : 'Status' }}</th>
                        <th>{{ $isAr ? 'الموجه' : 'Mentor' }}</th>
                        <th>{{ $isAr ? 'التقدم' : 'Progress' }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($projects as $project)
                    @php
                        $totalStages = $project->stages->count();
                        $completed = $project->stages->where('status', 'completed')->count();
                        $progress = $totalStages ? intval(($completed / $totalStages) * 100) : 0;
                    @endphp
                    <tr>
                        <td>{{ $project->title }}</td>
                        <td><x-status-badge :status="$project->status" /></td>
                        <td>{{ optional($project->mentor)->name }}</td>
                        <td style="min-width: 120px;">
                            <div class="progress ep-progress"><div class="progress-bar" style="width: {{ $progress }}%"></div></div>
                            <small class="text-muted">{{ $progress }}%</small>
                        </td>
                        <td class="d-flex gap-2 flex-nowrap">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('entrepreneur.projects.show', $project) }}">{{ $isAr ? 'فتح' : 'Open' }}</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('entrepreneur.projects.edit', $project) }}">{{ $isAr ? 'تعديل' : 'Edit' }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $projects->links() }}</div>
    @endif
</div>
@endsection
