@extends('layouts.app')
@section('title', 'Stages')
@section('content')
<a class="btn btn-primary mb-3" href="{{ route('admin.stages.create') }}">Create Stage</a>
<x-filter-bar>
    <div class="col-md-4">
        <label class="form-label">Search Project</label>
        <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Project title">
    </div>
    <div class="col-md-4">
        <label class="form-label">Project</label>
        <select class="form-select" name="project_id">
            <option value="">All projects</option>
            @foreach($projectOptions as $projectOption)
                <option value="{{ $projectOption->id }}" @if(($filters['project_id'] ?? '') == $projectOption->id) selected @endif>{{ $projectOption->title }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>
@if($projects->isEmpty())
    <x-empty-state title="No projects with stages found." />
@else
    @foreach($projects as $project)
        <div class="card mb-3">
            <div class="card-header"><strong>{{ $project->title }}</strong></div>
            <div class="card-body">
                @if($project->stages->isEmpty())
                    <x-empty-state title="No stages yet for this project." />
                @else
                    <table class="table">
                        <thead><tr><th>Order</th><th>Name</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                        @foreach($project->stages as $stage)
                            <tr>
                                <td>{{ $stage->stage_order }}</td>
                                <td>{{ $stage->name }}</td>
                                <td><x-status-badge :status="$stage->status" /></td>
                                <td class="d-flex gap-2">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.stages.edit', $stage) }}">Edit</a>
                                    <form method="post" action="{{ route('admin.stages.destroy', $stage) }}">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endforeach
    <div class="mt-3">{{ $projects->links() }}</div>
@endif
@endsection

