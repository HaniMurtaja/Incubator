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
        $isAdminArea = request()->routeIs('admin.*') || request()->is('admin/*');
        $isAr = app()->getLocale() === 'ar';
        $adminArabicLayout = $isAdminArea && $isAr;
    @endphp
    <style>
        body { font-family: 'Cairo', sans-serif; }
        @if($adminArabicLayout)
        /* Move desktop vertical sidebar to the right for Arabic admin pages */
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
<body class="{{ $adminArabicLayout ? 'admin-ar-layout' : '' }}">
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
                    <a href="{{ route('locale.switch', 'ar') }}" class="btn btn-outline-light btn-sm">{{ __('ui.switch_ar') }}</a>
                    <a href="{{ route('locale.switch', 'en') }}" class="btn btn-outline-light btn-sm">{{ __('ui.switch_en') }}</a>
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary btn-sm">
                        Notifications
                        @if(auth()->user()->unreadNotifications->count())
                            <span class="badge bg-red ms-1">{{ auth()->user()->unreadNotifications->count() }}</span>
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
@stack('scripts')
</body>
</html>

