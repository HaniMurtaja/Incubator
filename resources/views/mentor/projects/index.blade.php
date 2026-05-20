@extends('layouts.app')
@section('title', __('ui.mentor_assigned_projects'))
@section('content')
<x-filter-bar>
    <div class="col-md-4">
        <label class="form-label">{{ __('ui.search') }}</label>
        <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('ui.search_project_title') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ui.status') }}</label>
        <select class="form-select" name="status">
            <option value="">{{ __('ui.all') }}</option>
            @foreach(['pending','accepted','rejected','in_progress','completed'] as $status)
                <option value="{{ $status }}" @if(($filters['status'] ?? '') === $status) selected @endif>{{ __('ui.statuses.'.$status) }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>

@if($projects->isEmpty())
    <x-empty-state :title="__('ui.no_assigned_projects')" />
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead><tr><th>{{ __('ui.title') }}</th><th>{{ __('ui.entrepreneur') }}</th><th>{{ __('ui.status') }}</th><th></th></tr></thead>
                <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->title }}</td>
                        <td>{{ optional($project->entrepreneur)->name }}</td>
                        <td><x-status-badge :status="$project->status" /></td>
                        <td><a class="btn btn-sm btn-outline-primary" href="{{ route('mentor.projects.show', $project) }}">{{ __('ui.open') }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $projects->links() }}</div>
@endif
@endsection
