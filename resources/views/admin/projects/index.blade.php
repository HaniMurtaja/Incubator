@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('title', $isAr ? 'مراجعة المشاريع' : 'Projects Review')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="h3 mb-0">{{ $isAr ? 'مراجعة المشاريع' : 'Projects Review' }}</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal">
        {{ $isAr ? '+ إضافة مشروع جديد' : '+ Add New Project' }}
    </button>
</div>

<div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProjectModalLabel">{{ $isAr ? 'إضافة مشروع جديد' : 'Add New Project' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('admin.projects.store') }}" class="row g-2">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">{{ $isAr ? 'عنوان المشروع' : 'Project title' }}</label>
                        <input class="form-control" name="title" placeholder="{{ $isAr ? 'عنوان المشروع' : 'Project Title' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ $isAr ? 'التصنيف' : 'Category' }}</label>
                        <input class="form-control" name="category" placeholder="{{ $isAr ? 'التصنيف' : 'Category' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ $isAr ? 'رائد الأعمال' : 'Entrepreneur' }}</label>
                        <select class="form-select" name="entrepreneur_id" required>
                            <option value="">{{ $isAr ? 'اختر…' : 'Choose…' }}</option>
                            @foreach($entrepreneurs as $entrepreneur)
                                <option value="{{ $entrepreneur->id }}">{{ $entrepreneur->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ $isAr ? 'الموجه (اختياري)' : 'Mentor (optional)' }}</label>
                        <select class="form-select" name="mentor_id">
                            <option value="">{{ $isAr ? '—' : '—' }}</option>
                            @foreach($mentors as $mentor)
                                <option value="{{ $mentor->id }}">{{ $mentor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ $isAr ? 'الوصف' : 'Description' }}</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="{{ $isAr ? 'وصف المشروع' : 'Project description' }}" required></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ $isAr ? 'إلغاء' : 'Cancel' }}</button>
                        <button type="submit" class="btn btn-primary">{{ $isAr ? 'إضافة مشروع' : 'Add Project' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<x-filter-bar>
    <div class="col-md-4">
        <label class="form-label">Search</label>
        <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Project title or description">
    </div>
    <div class="col-md-2">
        <label class="form-label">Status</label>
        <select class="form-select" name="status">
            <option value="">All</option>
            @foreach(['pending','accepted','rejected','in_progress','completed'] as $status)
                <option value="{{ $status }}" @if(($filters['status'] ?? '') === $status) selected @endif>{{ str_replace('_',' ',$status) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Mentor</label>
        <select class="form-select" name="mentor_id">
            <option value="">All</option>
            @foreach($mentors as $mentor)
                <option value="{{ $mentor->id }}" @if(($filters['mentor_id'] ?? '') == $mentor->id) selected @endif>{{ $mentor->name }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>

@if($projects->isEmpty())
    <x-empty-state title="No projects found." />
@else
<div class="card">
    <div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead><tr><th>Title</th><th>Entrepreneur</th><th>Status</th><th>Mentor</th><th>Action</th></tr></thead>
        <tbody>
        @foreach($projects as $project)
            <tr>
                <td>{{ $project->title }}</td>
                <td>{{ optional($project->entrepreneur)->name }}</td>
                <td><x-status-badge :status="$project->status" /></td>
                <td>{{ optional($project->mentor)->name }}</td>
                <td>
                    <form method="post" action="{{ route('admin.projects.review', $project) }}" class="d-flex gap-2">
                        @csrf
                        @method('patch')
                        <select class="form-select form-select-sm" name="decision" required>
                            <option value="accepted">Accept</option>
                            <option value="rejected">Reject</option>
                        </select>
                        <select class="form-select form-select-sm" name="mentor_id">
                            <option value="">(assign mentor)</option>
                            @foreach($mentors as $mentor)
                                <option value="{{ $mentor->id }}">{{ $mentor->name }}</option>
                            @endforeach
                        </select>
                        <input class="form-control form-control-sm" name="decision_notes" placeholder="Notes">
                        <button class="btn btn-sm btn-primary">Update</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
<div class="mt-3">{{ $projects->links() }}</div>
@endif
@endsection
