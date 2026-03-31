@extends('layouts.app')
@section('title', 'Users')
@section('content')
<a class="btn btn-primary mb-3" href="{{ route('admin.users.create') }}">Create User</a>
<x-filter-bar>
    <div class="col-md-4">
        <label class="form-label">Search</label>
        <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Name or email">
    </div>
    <div class="col-md-3">
        <label class="form-label">Role</label>
        <select class="form-select" name="role">
            <option value="">All</option>
            @foreach(['Admin', 'Mentor', 'Entrepreneur'] as $role)
                <option value="{{ $role }}" @if(($filters['role'] ?? '') === $role) selected @endif>{{ $role }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>

@if($users->isEmpty())
    <x-empty-state title="No users found." />
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->getRoleNames()->implode(', ') }}</td>
                        <td class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            <form method="post" action="{{ route('admin.users.destroy', $user) }}">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $users->links() }}</div>
@endif
@endsection

