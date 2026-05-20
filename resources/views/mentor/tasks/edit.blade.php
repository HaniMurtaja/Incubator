@extends('layouts.app')
@section('title', __('ui.mentor_edit_task'))
@section('content')
<form method="post" action="{{ route('mentor.tasks.update', $task) }}" class="card card-body">
    @csrf
    @method('put')
    @include('mentor.tasks.partials.form')
    <button class="btn btn-primary mt-3">{{ __('ui.update') }}</button>
</form>
@endsection
