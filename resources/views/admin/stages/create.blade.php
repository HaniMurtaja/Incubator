@extends('layouts.app')
@section('title', __('ui.admin_create_stage'))
@section('content')
<form method="post" action="{{ route('admin.stages.store') }}" class="card card-body">
    @csrf
    @include('admin.stages.partials.form')
    <button class="btn btn-primary mt-3">{{ __('ui.save') }}</button>
</form>
@endsection
