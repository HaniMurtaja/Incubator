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
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @php
        $isAr = app()->getLocale() === 'ar';
        $rtlDashboardLayout = auth()->check() && $isAr;
    @endphp
    <style>
        /* ── Global base ── */
        body {
            font-family: 'Cairo', sans-serif;
            background: #E8ECF2 !important;
        }
        .page-wrapper {
            background: #E8ECF2 !important;
        }
        .page-wrapper .container-xl {
            background: transparent;
        }

        /* ══════════════════════════════════
           SIDEBAR
        ══════════════════════════════════ */
        aside.navbar.navbar-vertical {
            background: #0F1724 !important;
            border-inline-end: none;
            box-shadow: 2px 0 16px rgba(0,0,0,.25);
            display: flex !important;
            flex-direction: column !important;
        }
        aside.navbar.navbar-vertical .container-fluid {
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
            min-height: 100vh;
            padding-bottom: 0 !important;
        }

        aside .navbar-brand {
            font-size: 16px !important;
            font-weight: 700 !important;
            color: #FFFFFF !important;
            letter-spacing: -.3px;
            padding-bottom: .875rem;
            border-bottom: 1px solid rgba(255,255,255,.10);
            margin-bottom: .75rem !important;
            display: block;
            flex-shrink: 0;
            text-align: center;
        }

        .sidebar-nav-links {
            flex: 1;
            overflow-y: auto;
        }
        aside .nav-link {
            font-size: 13px !important;
            font-weight: 500 !important;
            color: #8896AA !important;
            padding: 8px 12px !important;
            border-radius: 8px !important;
            margin-bottom: 2px;
            transition: background .15s, color .15s !important;
            border-inline-start: 3px solid transparent;
            display: block;
        }
        aside .nav-link:hover {
            background: rgba(255,255,255,.07) !important;
            color: #EEF3F7 !important;
            border-inline-start-color: #1A56DB;
        }
        aside .nav-link.active,
        aside .nav-link[aria-current="page"] {
            background: #1A56DB !important;
            color: #fff !important;
            font-weight: 600 !important;
            border-inline-start-color: transparent;
        }

        .sidebar-footer {
            flex-shrink: 0;
            border-top: 1px solid rgba(255,255,255,.10);
            padding: 1rem .75rem 1.25rem;
        }

        .lang-row {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: .875rem;
        }

        .lang-btn {
            padding: 5px 14px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            color: #ffffff !important;
            background: #1A56DB;
            transition: background .15s, transform .12s;
        }
        .lang-btn:hover {
            background: #1448b8;
            color: #ffffff !important;
            text-decoration: none;
            transform: translateY(-1px);
        }
        .lang-btn.active-lang {
            background: #0B7B5C;
        }
        .lang-btn.active-lang:hover {
            background: #085041;
        }

        .logout-btn {
            display: block;
            width: 100%;
            padding: 10px 16px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            border: 1.5px solid rgba(220,70,70,.45);
            background: rgba(192,57,43,.12);
            color: #F08080 !important;
            cursor: pointer;
            transition: background .15s, color .15s, border-color .15s;
            font-family: 'Cairo', sans-serif;
        }
        .logout-btn:hover {
            background: #C0392B !important;
            border-color: #C0392B !important;
            color: #ffffff !important;
        }

        /* ══════════════════════════════════
           TOPBAR
        ══════════════════════════════════ */
        .page-header {
            background: #FFFFFF;
            border-radius: 10px;
            padding: .75rem 1.25rem;
            margin-bottom: 1.25rem !important;
            border: 1px solid #DDE2EC;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .page-title {
            font-size: 17px !important;
            font-weight: 700 !important;
            color: #0F1724 !important;
            margin: 0;
        }

        .notif-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,.18);
            background: rgba(255,255,255,.07);
            color: #8896AA;
            text-decoration: none;
            transition: border-color .15s, background .15s, color .15s;
            flex-shrink: 0;
        }
        .notif-btn:hover {
            border-color: #1A56DB;
            background: rgba(26,86,219,.25);
            color: #7EC8F5;
            text-decoration: none;
        }
        .notif-btn svg {
            width: 17px;
            height: 17px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }
        .notif-count {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 16px;
            height: 16px;
            background: #C0392B;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
            line-height: 1;
            border: 2px solid #0F1724;
        }

        .user-badge {
            font-size: 13px;
            font-weight: 600;
            color: #4A5568;
            background: #F1F3F7;
            border: 1px solid #DDE2EC;
            border-radius: 20px;
            padding: 5px 13px;
        }

        .btn-outline-secondary:hover,
        .btn-outline-secondary:focus {
            background: #1A56DB !important;
            border-color: #1A56DB !important;
            color: #fff !important;
        }
        .btn-outline-primary:hover,
        .btn-outline-primary:focus {
            background: #0B7B5C !important;
            border-color: #0B7B5C !important;
            color: #fff !important;
        }
        .btn-dark:hover,
        .btn-dark:focus {
            background: #1A56DB !important;
            border-color: #1A56DB !important;
            color: #fff !important;
        }

        html[dir="rtl"] .table th,
        html[dir="rtl"] .table td {
            text-align: right;
        }
        html[dir="rtl"] .form-label {
            text-align: right;
        }
        html[dir="rtl"] .page-header {
            flex-direction: row-reverse;
        }

        @if($rtlDashboardLayout)
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
            .lang-row { justify-content: flex-start; }
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
    $currentLocale = app()->getLocale();
@endphp

<div class="page">

    {{-- ══ SIDEBAR ══ --}}
    <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark" style="min-height: 100vh;">
        <div class="container-fluid">

            <h1 class="navbar-brand text-white mb-4">{{ __('ui.app_name') }}</h1>

            <div class="navbar-nav flex-column w-100 sidebar-nav-links">
                @include('partials.sidebar')
            </div>

            <div class="sidebar-footer">

                <div class="lang-row">
                    <a href="{{ route('notifications.index') }}"
                       class="notif-btn"
                       title="{{ $currentLocale === 'ar' ? 'الإشعارات' : 'Notifications' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 01-3.46 0"/>
                        </svg>
                        @if($unreadNotificationsCount > 0)
                            <span class="notif-count">{{ $unreadNotificationsCount }}</span>
                        @endif
                    </a>

                    <span style="flex:1;"></span>

                    <a href="{{ route('locale.switch', 'ar') }}"
                       class="lang-btn {{ $currentLocale === 'ar' ? 'active-lang' : '' }}">
                        ع
                    </a>
                    <a href="{{ route('locale.switch', 'en') }}"
                       class="lang-btn {{ $currentLocale === 'en' ? 'active-lang' : '' }}">
                        EN
                    </a>
                </div>

                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-btn">
                        {{ $currentLocale === 'ar' ? 'تسجيل الخروج' : 'Logout' }}
                    </button>
                </form>

            </div>
        </div>
    </aside>

    {{-- ══ MAIN CONTENT ══ --}}
    <div class="page-wrapper">
        <div class="container-xl mt-3">

            <div class="page-header mb-3">
                <h2 class="page-title">@yield('title', 'لوحة التحكم')</h2>
                <span class="user-badge">{{ auth()->user()->name }}</span>
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
