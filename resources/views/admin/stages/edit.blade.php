@extends('layouts.app')
@section('title', 'Edit Stage')
@section('content')
<form method="post" action="{{ route('admin.stages.update', $stage) }}" class="card card-body">
    @csrf
    @method('put')
    @include('admin.stages.partials.form')
    <button class="btn btn-primary mt-3">Update</button>
</form>
@endsection

