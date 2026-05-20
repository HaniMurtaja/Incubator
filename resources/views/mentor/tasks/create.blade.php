@extends('layouts.app')
@section('title', __('ui.mentor_create_task'))
@section('content')
<form method="post" action="{{ route('mentor.tasks.store') }}" class="card card-body">
    @csrf
    @include('mentor.tasks.partials.form')
    <button class="btn btn-primary mt-3">{{ __('ui.save') }}</button>
</form>
@endsection
