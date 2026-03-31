@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
<form method="post" action="{{ route('admin.users.update', $user) }}" class="card card-body">
    @csrf
    @method('put')
    @include('admin.users.partials.form')
    <button class="btn btn-primary mt-3">Update</button>
</form>
@endsection

