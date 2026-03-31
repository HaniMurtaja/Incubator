@extends('layouts.app')
@section('title', 'Create User')
@section('content')
<form method="post" action="{{ route('admin.users.store') }}" class="card card-body">
    @csrf
    @include('admin.users.partials.form')
    <button class="btn btn-primary mt-3">Save</button>
</form>
@endsection

@extends('layouts.app')
@section('title', 'Create User')
@section('content')
<form method="post" action="{{ route('admin.users.store') }}" class="card card-body">
    @csrf
    @include('admin.users.partials.form')
    <button class="btn btn-primary mt-3">Save</button>
</form>
@endsection

