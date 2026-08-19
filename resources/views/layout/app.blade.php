<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'LumiKids')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --lk-sidebar-bg: #262B3D;
            --lk-sidebar-border: rgba(255, 255, 255, .08);
            --lk-text-muted: #9CA3B8;
            --lk-text-label: #6B7280;
            --lk-active-bg: #4C7DF5;
            --lk-content-bg: #EEF1F8;
            --lk-badge: #EF4444;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--lk-content-bg);
            margin: 0;
        }

        .lk-layout {
            display: flex;
            min-height: 100vh;
        }

        .lk-sidebar {
            width: 260px;
            flex-shrink: 0;
            background: var(--lk-sidebar-bg);
            padding: 1.5rem 1rem;
        }

        .lk-sidebar-header {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--lk-sidebar-border);
            margin-bottom: 1.5rem;
        }

        .lk-logo-icon {
            font-size: 1.75rem;
            color: #fff;
        }

        .lk-logo-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .lk-logo-title {
            color: #fff;
            font-weight: 700;
            font-size: 1.15rem;
            line-height: 1.1;
        }

        .lk-logo-subtitle {
            color: var(--lk-text-label);
            font-size: .7rem;
        }

        .lk-nav-section {
            margin-bottom: 1.75rem;
        }

        .lk-nav-label {
            display: block;
            color: var(--lk-text-label);
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-bottom: .75rem;
            padding-left: .5rem;
        }

        .lk-nav-item {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem 1rem;
            border-radius: 999px;
            color: var(--lk-text-muted);
            text-decoration: none;
            font-size: .9rem;
            font-weight: 500;
            margin-bottom: .25rem;
            transition: background .15s ease, color .15s ease;
        }

        .lk-nav-item:hover {
            background: rgba(255, 255, 255, .05);
            color: #fff;
        }

        .lk-nav-item.active {
            background: var(--lk-active-bg);
            color: #fff;
            font-weight: 600;
        }

        .lk-badge {
            margin-left: auto;
            background: var(--lk-badge);
            color: #fff;
            font-size: .7rem;
            font-weight: 700;
            border-radius: 999px;
            padding: .1rem .5rem;
        }

        .lk-content {
            flex: 1;
            padding: 2rem;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="lk-layout">
        @include('partials.sidebar')
        <main class="lk-content">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>