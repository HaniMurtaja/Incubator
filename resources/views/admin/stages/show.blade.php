@extends('layouts.app')
@section('title', __('ui.admin_stage_details'))
@section('content')
<div class="card card-body">
    <div><strong>{{ __('ui.project') }}:</strong> {{ optional($stage->project)->title }}</div>
    <div><strong>{{ __('ui.name') }}:</strong> {{ $stage->name }}</div>
    <div><strong>{{ __('ui.order') }}:</strong> {{ $stage->stage_order }}</div>
    <div><strong>{{ __('ui.status') }}:</strong> <x-status-badge :status="$stage->status" /></div>
</div>
@endsection
