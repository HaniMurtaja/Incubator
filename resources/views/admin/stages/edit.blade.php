@extends('layouts.app')
@section('title', __('ui.admin_edit_stage'))
@section('content')
<form method="post" action="{{ route('admin.stages.update', $stage) }}" class="card card-body">
    @csrf
    @method('put')
    @include('admin.stages.partials.form')
    <button class="btn btn-primary mt-3">{{ __('ui.update') }}</button>
</form>
@endsection
