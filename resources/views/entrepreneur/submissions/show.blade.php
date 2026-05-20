@extends('layouts.app')
@section('title', __('ui.submission_number', ['id' => $submission->id]))
@section('content')
@php
    $decisionKey = $submission->evaluation->decision ?? '';
    $decisionLabel = $decisionKey ? (__('ui.statuses.'.$decisionKey) !== 'ui.statuses.'.$decisionKey ? __('ui.statuses.'.$decisionKey) : $decisionKey) : '';
@endphp
<div class="card mb-3"><div class="card-body">
    <div><strong>{{ __('ui.task') }}:</strong> {{ optional($submission->task)->title }}</div>
    <div><strong>{{ __('ui.status') }}:</strong> <x-status-badge :status="$submission->status" /></div>
    <div class="mt-2">{{ $submission->notes }}</div>
</div></div>

@if($submission->evaluation)
<div class="card">
    <div class="card-header">{{ __('ui.mentor_feedback') }}</div>
    <div class="card-body">
        <div><strong>{{ __('ui.decision') }}:</strong> {{ $decisionLabel }}</div>
        <div>{{ $submission->evaluation->comments }}</div>
    </div>
</div>
@endif
@endsection
