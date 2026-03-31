@extends('layouts.app')
@section('title', 'Tasks')
@section('content')
<a class="btn btn-primary mb-3" href="{{ route('mentor.tasks.create') }}">Create Task</a>
<x-filter-bar>
    <div class="col-md-4">
        <label class="form-label">Search</label>
        <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Task title or description">
    </div>
    <div class="col-md-3">
        <label class="form-label">Status</label>
        <select class="form-select" name="status">
            <option value="">All</option>
            @foreach(['not_started','in_progress','submitted','changes_requested','approved'] as $status)
                <option value="{{ $status }}" @if(($filters['status'] ?? '') === $status) selected @endif>{{ str_replace('_',' ',$status) }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>
@if($tasks->isEmpty())
    <x-empty-state title="No tasks found." />
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead><tr><th>Task</th><th>Project</th><th>Due</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ optional(optional($task->stage)->project)->title }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td><x-status-badge :status="$task->status" /></td>
                        <td class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('mentor.tasks.show', $task) }}">View</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('mentor.tasks.edit', $task) }}">Edit</a>
                            <form method="post" action="{{ route('mentor.tasks.destroy', $task) }}">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $tasks->links() }}</div>
@endif
@endsection

