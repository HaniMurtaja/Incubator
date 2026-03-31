<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 | Not Found</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta21/dist/css/tabler.min.css">
</head>
<body class="d-flex flex-column">
<div class="page page-center">
    <div class="container container-tight py-4 text-center">
        <h1 class="display-4">404</h1>
        <p class="text-muted">The page you are looking for does not exist.</p>
        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Back to dashboard</a>
    </div>
</div>
</body>
</html>

