@extends('layouts.app')
@section('title', 'Assigned Projects')
@section('content')
<x-filter-bar>
    <div class="col-md-4">
        <label class="form-label">Search</label>
        <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Title or description">
    </div>
    <div class="col-md-3">
        <label class="form-label">Status</label>
        <select class="form-select" name="status">
            <option value="">All</option>
            @foreach(['pending','accepted','rejected','in_progress','completed'] as $status)
                <option value="{{ $status }}" @if(($filters['status'] ?? '') === $status) selected @endif>{{ str_replace('_',' ',$status) }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>

@if($projects->isEmpty())
    <x-empty-state title="No assigned projects found." />
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead><tr><th>Title</th><th>Entrepreneur</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->title }}</td>
                        <td>{{ optional($project->entrepreneur)->name }}</td>
                        <td><x-status-badge :status="$project->status" /></td>
                        <td><a class="btn btn-sm btn-outline-primary" href="{{ route('mentor.projects.show', $project) }}">Open</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $projects->links() }}</div>
@endif
@endsection

