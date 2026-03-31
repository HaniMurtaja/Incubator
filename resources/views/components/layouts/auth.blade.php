<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta21/dist/css/tabler.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .role-card { transition: all .25s ease; }
        .role-card:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,.08); }
    </style>
</head>
<body class="border-top-wide border-primary d-flex flex-column">
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-end mb-3">
            <a href="{{ route('locale.switch', 'ar') }}" class="btn btn-sm btn-outline-secondary">{{ __('ui.switch_ar') }}</a>
            <a href="{{ route('locale.switch', 'en') }}" class="btn btn-sm btn-outline-secondary">{{ __('ui.switch_en') }}</a>
        </div>
        <div class="text-center mb-4">
            <a href="{{ url('/') }}" class="navbar-brand navbar-brand-autodark">
                {{ __('ui.app_name') }}
            </a>
        </div>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        {{ $slot }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta21/dist/js/tabler.min.js"></script>
</body>
</html>

