<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'INCULAB') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta21/dist/css/tabler.min.css">
    @php
        $isAr = app()->getLocale() === 'ar';
        $rtlDashboardLayout = auth()->check() && $isAr;
    @endphp
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .locale-switch {
            border: 0;
            color: #fff !important;
            font-weight: 700;
            box-shadow: 0 .2rem .65rem rgba(0, 0, 0, .15);
        }
        .locale-switch-ar { background: #1d4ed8; }
        .locale-switch-en { background: #2563eb; }
        @if($rtlDashboardLayout)
        /* Move desktop vertical sidebar to the right for Arabic dashboard pages */
        @media (min-width: 992px) {
            .page {
                --admin-sidebar-width: var(--tblr-navbar-width, 15rem);
            }
            .navbar-vertical.navbar-expand-lg {
                width: var(--admin-sidebar-width) !important;
                left: auto !important;
                right: 0 !important;
            }
            .navbar-vertical.navbar-expand-lg ~ .page-wrapper {
                margin-left: 0 !important;
                margin-right: var(--admin-sidebar-width) !important;
                width: calc(100% - var(--admin-sidebar-width)) !important;
                max-width: calc(100% - var(--admin-sidebar-width)) !important;
            }
        }
        @endif
    </style>
    @stack('styles')
</head>
<body class="{{ $rtlDashboardLayout ? 'admin-ar-layout' : '' }}">
@php
    $unreadNotificationsCount = 0;
    if (auth()->check()) {
        try {
            $unreadNotificationsCount = auth()->user()->unreadNotifications()->count();
        } catch (\Throwable $e) {
            $unreadNotificationsCount = 0;
        }
    }
@endphp
<div class="page">
    <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark bg-dark" style="min-height: 100vh;">
        <div class="container-fluid">
            <h1 class="navbar-brand text-white mb-4">{{ __('ui.app_name') }}</h1>
            <div class="navbar-nav flex-column w-100">
                @include('partials.sidebar')
            </div>
        </div>
    </aside>
    <div class="page-wrapper">
        <div class="container-xl mt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="page-title">@yield('title', 'لوحة التحكم')</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('locale.switch', 'ar') }}" class="btn btn-sm locale-switch locale-switch-ar">{{ __('ui.switch_ar') }}</a>
                    <a href="{{ route('locale.switch', 'en') }}" class="btn btn-sm locale-switch locale-switch-en">{{ __('ui.switch_en') }}</a>
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary btn-sm">
                        Notifications
                        @if($unreadNotificationsCount > 0)
                            <span class="badge bg-red ms-1">{{ $unreadNotificationsCount }}</span>
                        @endif
                    </a>
                    <span class="badge bg-azure-lt">{{ auth()->user()->name }}</span>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm">logout</button>
                    </form>
                </div>
            </div>

            @if (session('status'))
                <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
                    <div class="alert alert-success shadow mb-0">{{ session('status') }}</div>
                </div>
            @endif

            <x-form-errors />

            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta21/dist/js/tabler.min.js"></script>
@stack('scripts')
</body>
</html>

