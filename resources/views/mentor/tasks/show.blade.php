@extends('layouts.app')
@section('title', $task->title)
@section('content')
<div class="card"><div class="card-body">
    <div><strong>{{ __('ui.stage') }}:</strong> {{ optional($task->stage)->name }}</div>
    <div><strong>{{ __('ui.status') }}:</strong> <x-status-badge :status="$task->status" /></div>
    <p class="mt-2">{{ $task->description }}</p>
</div></div>
@endsection
