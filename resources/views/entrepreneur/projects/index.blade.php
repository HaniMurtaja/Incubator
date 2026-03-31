@extends('layouts.app')
@section('title', 'My Projects')
@section('content')
<a class="btn btn-primary mb-3" href="{{ route('entrepreneur.projects.create') }}">Submit New Project</a>
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
    <x-empty-state title="No projects found." />
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead><tr><th>Title</th><th>Status</th><th>Mentor</th><th>Progress</th><th></th></tr></thead>
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
                        <td>
                            <div class="progress"><div class="progress-bar" style="width: {{ $progress }}%">{{ $progress }}%</div></div>
                        </td>
                        <td class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('entrepreneur.projects.show', $project) }}">Open</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('entrepreneur.projects.edit', $project) }}">Edit</a>
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

