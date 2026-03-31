@extends('layouts.app')
@section('title', 'Create Task')
@section('content')
<form method="post" action="{{ route('mentor.tasks.store') }}" class="card card-body">
    @csrf
    @include('mentor.tasks.partials.form')
    <button class="btn btn-primary mt-3">Save</button>
</form>
@endsection

