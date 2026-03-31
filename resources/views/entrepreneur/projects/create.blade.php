@extends('layouts.app')
@section('title', 'Submit Project')
@section('content')
<form method="post" action="{{ route('entrepreneur.projects.store') }}" enctype="multipart/form-data" class="card card-body">
    @csrf
    @include('entrepreneur.projects.partials.form')
    <button class="btn btn-primary mt-3">Submit</button>
</form>
@endsection

