@extends('layouts.app')
@section('title', $project->title)
@section('content')
<div class="card mb-3"><div class="card-body">
    <div><strong>Status:</strong> <span class="badge bg-blue-lt">{{ $project->status }}</span></div>
    <div><strong>Mentor:</strong> {{ optional($project->mentor)->name ?? '-' }}</div>
    <p class="mt-2">{{ $project->description }}</p>
</div></div>

<div class="card mb-3">
    <div class="card-header">Incubation Timeline</div>
    <div class="card-body">
        @foreach($project->stages as $stage)
            <div class="mb-2">
                <strong>{{ $stage->stage_order }}. {{ $stage->name }}</strong>
                <span class="badge bg-azure-lt">{{ $stage->status }}</span>
            </div>
        @endforeach
    </div>
</div>

<div class="card">
    <div class="card-header">Activity Log</div>
    <div class="list-group list-group-flush">
        @forelse($project->activityLogs as $log)
            <div class="list-group-item">{{ $log->event }} <small class="text-muted">{{ $log->created_at }}</small></div>
        @empty
            <div class="list-group-item text-muted">No activity yet.</div>
        @endforelse
    </div>
</div>
@endsection

