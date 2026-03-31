@extends('layouts.app')
@section('title', 'Create Stage')
@section('content')
<form method="post" action="{{ route('admin.stages.store') }}" class="card card-body">
    @csrf
    @include('admin.stages.partials.form')
    <button class="btn btn-primary mt-3">Save</button>
</form>
@endsection

