@extends('layouts.app')
@section('title', __('ui.mentor_tasks'))
@section('content')
<a class="btn btn-primary mb-3" href="{{ route('mentor.tasks.create') }}">{{ __('ui.mentor_create_task') }}</a>
<x-filter-bar>
    <div class="col-md-4">
        <label class="form-label">{{ __('ui.search') }}</label>
        <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('ui.search_task') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ui.status') }}</label>
        <select class="form-select" name="status">
            <option value="">{{ __('ui.all') }}</option>
            @foreach(['not_started','in_progress','submitted','changes_requested','approved'] as $status)
                <option value="{{ $status }}" @if(($filters['status'] ?? '') === $status) selected @endif>{{ __('ui.statuses.'.$status) }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>
@if($tasks->isEmpty())
    <x-empty-state :title="__('ui.no_tasks')" />
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead><tr><th>{{ __('ui.task') }}</th><th>{{ __('ui.project') }}</th><th>{{ __('ui.due') }}</th><th>{{ __('ui.status') }}</th><th></th></tr></thead>
                <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ optional(optional($task->stage)->project)->title }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td><x-status-badge :status="$task->status" /></td>
                        <td class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('mentor.tasks.show', $task) }}">{{ __('ui.view') }}</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('mentor.tasks.edit', $task) }}">{{ __('ui.edit') }}</a>
                            <form method="post" action="{{ route('mentor.tasks.destroy', $task) }}">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">{{ __('ui.delete') }}</button></form>
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
