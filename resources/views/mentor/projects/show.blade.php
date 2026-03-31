@extends('layouts.app')
@section('title', $project->title)
@section('content')
<div class="card mb-3"><div class="card-body">
    <div><strong>Entrepreneur:</strong> {{ optional($project->entrepreneur)->name }}</div>
    <div><strong>Status:</strong> <span class="badge bg-blue-lt">{{ $project->status }}</span></div>
</div></div>

@foreach($project->stages as $stage)
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between"><span>{{ $stage->stage_order }}. {{ $stage->name }}</span><span class="badge bg-azure-lt">{{ $stage->status }}</span></div>
        <div class="card-body">
            @foreach($stage->tasks as $task)
                <div class="border rounded p-2 mb-2">
                    <div class="d-flex justify-content-between"><strong>{{ $task->title }}</strong><span class="badge bg-indigo-lt">{{ $task->status }}</span></div>
                    <small class="text-muted">{{ $task->description }}</small>
                    @foreach($task->submissions as $submission)
                        <div class="mt-2 p-2 bg-light rounded">
                            <div>Submission #{{ $submission->id }} - {{ $submission->status }}</div>
                            <div>{{ $submission->notes }}</div>
                            <form method="post" action="{{ route('mentor.submissions.evaluate', $submission) }}" class="d-flex gap-2 mt-2">
                                @csrf
                                <select class="form-select form-select-sm" name="decision">
                                    <option value="approved">Approve</option>
                                    <option value="changes_requested">Request changes</option>
                                </select>
                                <input class="form-control form-control-sm" name="comments" placeholder="Feedback">
                                <button class="btn btn-sm btn-primary">Submit</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endforeach

<div class="card">
    <div class="card-header">Activity Timeline</div>
    <div class="list-group list-group-flush">
        @forelse($project->activityLogs as $log)
            <div class="list-group-item">
                <strong>{{ $log->event }}</strong>
                <div class="text-muted small">{{ $log->created_at }}</div>
            </div>
        @empty
            <div class="list-group-item text-muted">No activity yet.</div>
        @endforelse
    </div>
</div>
@endsection

