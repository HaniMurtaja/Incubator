@extends('layouts.app')
@section('title', 'Submission #'.$submission->id)
@section('content')
<div class="card mb-3"><div class="card-body">
    <div><strong>Task:</strong> {{ optional($submission->task)->title }}</div>
    <div><strong>Status:</strong> <span class="badge bg-azure-lt">{{ $submission->status }}</span></div>
    <div class="mt-2">{{ $submission->notes }}</div>
</div></div>

@if($submission->evaluation)
<div class="card">
    <div class="card-header">Mentor Feedback</div>
    <div class="card-body">
        <div><strong>Decision:</strong> {{ $submission->evaluation->decision }}</div>
        <div>{{ $submission->evaluation->comments }}</div>
    </div>
</div>
@endif
@endsection

