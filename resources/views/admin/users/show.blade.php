@extends('layouts.app')
@section('title', 'User Details')
@section('content')
<div class="card card-body">
    <div><strong>Name:</strong> {{ $user->name }}</div>
    <div><strong>Email:</strong> {{ $user->email }}</div>
    <div><strong>Role:</strong> {{ $user->getRoleNames()->implode(', ') }}</div>
</div>
@endsection

