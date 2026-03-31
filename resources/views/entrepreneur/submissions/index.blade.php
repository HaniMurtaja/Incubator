@extends('layouts.app')
@section('title', 'My Submissions')
@section('content')
<a class="btn btn-primary mb-3" href="{{ route('entrepreneur.submissions.create') }}">New Submission</a>
<x-filter-bar>
    <div class="col-md-4">
        <label class="form-label">Search Task</label>
        <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Task title">
    </div>
    <div class="col-md-3">
        <label class="form-label">Status</label>
        <select class="form-select" name="status">
            <option value="">All</option>
            @foreach(['submitted','under_review','approved','changes_requested'] as $status)
                <option value="{{ $status }}" @if(($filters['status'] ?? '') === $status) selected @endif>{{ str_replace('_',' ',$status) }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>
@if($submissions->isEmpty())
    <x-empty-state title="No submissions found." />
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead><tr><th>Task</th><th>Project</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach($submissions as $submission)
                    <tr>
                        <td>{{ optional($submission->task)->title }}</td>
                        <td>{{ optional(optional(optional($submission->task)->stage)->project)->title }}</td>
                        <td><x-status-badge :status="$submission->status" /></td>
                        <td><a class="btn btn-sm btn-outline-primary" href="{{ route('entrepreneur.submissions.show', $submission) }}">View</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $submissions->links() }}</div>
@endif
@endsection

