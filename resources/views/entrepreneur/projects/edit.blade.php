@extends('layouts.app')
@section('title', 'Edit Project')
@section('content')
<form method="post" action="{{ route('entrepreneur.projects.update', $project) }}" enctype="multipart/form-data" class="card card-body">
    @csrf
    @method('put')
    @include('entrepreneur.projects.partials.form')
    <button class="btn btn-primary mt-3">Update</button>
</form>
@endsection

