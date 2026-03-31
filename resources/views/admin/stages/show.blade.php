@extends('layouts.app')
@section('title', 'Stage Details')
@section('content')
<div class="card card-body">
    <div><strong>Project:</strong> {{ optional($stage->project)->title }}</div>
    <div><strong>Name:</strong> {{ $stage->name }}</div>
    <div><strong>Order:</strong> {{ $stage->stage_order }}</div>
    <div><strong>Status:</strong> {{ $stage->status }}</div>
</div>
@endsection

