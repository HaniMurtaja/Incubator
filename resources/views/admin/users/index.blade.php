@extends('layouts.app')
@php $isAr = app()->getLocale() === 'ar'; @endphp
@section('title', $isAr ? 'المستخدمون' : 'Users')
@section('content')
<style>
.users-kpi {
    border: 0;
    border-radius: 1rem;
    background: linear-gradient(135deg, #ffffff 0%, #eef4ff 100%);
    box-shadow: 0 .55rem 1.1rem rgba(15, 23, 42, .08);
    transition: transform .2s ease, box-shadow .2s ease;
}
.users-kpi:hover {
    transform: translateY(-4px);
    box-shadow: 0 .9rem 1.5rem rgba(37, 99, 235, .16);
}
.users-kpi .kpi-label {
    font-size: .92rem;
    font-weight: 700;
}
.users-kpi .kpi-value {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.1;
}
.users-kpi-icon {
    width: 2.6rem;
    height: 2.6rem;
    border-radius: .75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.users-kpi-icon svg {
    width: 1.15rem;
    height: 1.15rem;
}
</style>
<div class="row row-cards mb-3">
    <div class="col-md-4">
        <div class="card card-body users-kpi">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted kpi-label">{{ $isAr ? 'عدد المديرين' : 'Admins count' }}</div>
                    <h3 class="mb-0 kpi-value">{{ $roleStats['admins'] ?? 0 }}</h3>
                </div>
                <div class="users-kpi-icon bg-primary-lt text-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-body users-kpi">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted kpi-label">{{ $isAr ? 'عدد الموجهين' : 'Mentors count' }}</div>
                    <h3 class="mb-0 kpi-value">{{ $roleStats['mentors'] ?? 0 }}</h3>
                </div>
                <div class="users-kpi-icon bg-azure-lt text-azure">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="7" r="4"></circle>
                        <path d="M5.5 21a6.5 6.5 0 0 1 13 0"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-body users-kpi">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted kpi-label">{{ $isAr ? 'عدد رواد الأعمال' : 'Entrepreneurs count' }}</div>
                    <h3 class="mb-0 kpi-value">{{ $roleStats['entrepreneurs'] ?? 0 }}</h3>
                </div>
                <div class="users-kpi-icon bg-green-lt text-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 20h16"></path>
                        <path d="M7 20V9l5-5 5 5v11"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<a class="btn btn-primary mb-3" href="{{ route('admin.users.create') }}">{{ $isAr ? 'إضافة مستخدم' : 'Create User' }}</a>
<x-filter-bar>
    <div class="col-md-4">
        <label class="form-label">{{ $isAr ? 'بحث' : 'Search' }}</label>
        <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ $isAr ? 'الاسم أو البريد الإلكتروني' : 'Name or email' }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ $isAr ? 'الدور' : 'Role' }}</label>
        <select class="form-select" name="role">
            <option value="">{{ $isAr ? 'الكل' : 'All' }}</option>
            @foreach(['Admin', 'Mentor', 'Entrepreneur'] as $role)
                <option value="{{ $role }}" @if(($filters['role'] ?? '') === $role) selected @endif>
                    {{ $isAr ? ($role === 'Admin' ? 'مدير النظام' : ($role === 'Mentor' ? 'موجه الأعمال' : 'رائد الأعمال')) : $role }}
                </option>
            @endforeach
        </select>
    </div>
</x-filter-bar>

@if($users->isEmpty())
    <x-empty-state :title="$isAr ? 'لا يوجد مستخدمون.' : 'No users found.'" />
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ $isAr ? 'الاسم' : 'Name' }}</th>
                        <th>{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</th>
                        <th>{{ $isAr ? 'الدور' : 'Role' }}</th>
                        <th>{{ $isAr ? 'الإجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->getRoleNames()->implode(', ') }}</td>
                        <td class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.edit', $user) }}">{{ $isAr ? 'تعديل' : 'Edit' }}</a>
                            <form method="post" action="{{ route('admin.users.destroy', $user) }}">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">{{ $isAr ? 'حذف' : 'Delete' }}</button></form>
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

