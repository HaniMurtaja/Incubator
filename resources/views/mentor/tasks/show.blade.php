@extends('layouts.app')
@section('title', $task->title)
@section('content')
<div class="card"><div class="card-body">
    <div><strong>Stage:</strong> {{ optional($task->stage)->name }}</div>
    <div><strong>Status:</strong> <span class="badge bg-azure-lt">{{ $task->status }}</span></div>
    <p class="mt-2">{{ $task->description }}</p>
</div></div>
@endsection

