<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'LumiKids')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --lk-sidebar-bg: #1B2141;
            --lk-sidebar-border: rgba(255, 255, 255, .08);
            --lk-primary: #4C7DF5;
            --lk-primary-hover: #3D68DD;
            --lk-text-muted: #9CA3B8;
            --lk-text-label: #6B7280;
            --lk-active-bg: #3B82F6;
            --lk-content-bg: #EEF1F8;
            --lk-badge: #EF4444;
            --lk-surface: #fff;
            --lk-surface-soft: #F7F9FF;
            --lk-border: #DFE5F1;
            --lk-border-soft: #EEF1F8;
            --lk-text: #262B3D;
            --lk-text-body: #333A54;
            --lk-primary-soft: #E9EFFF;
            --lk-success-soft: #E9F9EF;
            --lk-success: #198754;
            --lk-danger-soft: #FDEBEC;
            --lk-danger: #DC3545;
            --lk-warning-soft: #FFF3CD;
            --lk-warning: #997404;
            --lk-info-soft: #E9EFFF;
            --lk-info: #4C7DF5;
            --lk-shadow: 0 2px 10px rgba(30, 40, 90, .05);
            --lk-radius-card: 16px;
            --lk-radius-control: 10px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--lk-content-bg);
            margin: 0;
        }

        .lk-layout {
            display: flex;
            min-height: 100vh;
        }

        .lk-sidebar {
            display: flex;
            flex-direction: column;
            width: 260px;
            flex-shrink: 0;
            background: var(--lk-sidebar-bg);
            padding: 1.5rem 1rem;
        }

        .lk-sidebar nav {
            flex: 1;
        }

        .lk-logout-form {
            margin-top: auto;
        }

        .lk-logout-button {
            width: 100%;
            border: 0;
            background: transparent;
            cursor: pointer;
            text-align: left;
            font-family: inherit;
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

        .lk-page-title {
            display: flex;
            align-items: center;
            gap: .85rem;
            margin: 0 0 1.5rem;
            color: var(--lk-text);
            font-size: 1.4rem;
            font-weight: 700;
        }

        .lk-page-title-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.75rem;
            height: 2.75rem;
            flex-shrink: 0;
            border-radius: .75rem;
            background: var(--lk-primary-soft);
            color: var(--lk-primary);
            font-size: 1.25rem;
        }

        .lk-surface {
            background: var(--lk-surface);
            border: 1px solid var(--lk-border);
            border-radius: var(--lk-radius-card);
            box-shadow: var(--lk-shadow);
        }

        .lk-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            min-height: 2.75rem;
            padding: .65rem 1rem;
            border: 0;
            border-radius: var(--lk-radius-control);
            background: var(--lk-primary);
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            transition: background .15s ease, transform .15s ease, box-shadow .15s ease;
        }

        .lk-btn-primary:hover {
            background: var(--lk-primary-hover);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 .3rem .7rem rgba(76, 125, 245, .2);
        }

        .lk-table-wrap {
            overflow-x: auto;
            border-radius: var(--lk-radius-card);
        }

        .lk-form-control,
        .lk-form-select {
            min-height: 2.75rem;
            border: 1px solid var(--lk-border);
            border-radius: var(--lk-radius-control);
            color: var(--lk-text);
            background: var(--lk-surface);
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .lk-form-control:focus,
        .lk-form-select:focus {
            border-color: var(--lk-primary);
            box-shadow: 0 0 0 .2rem rgba(76, 125, 245, .15);
        }

        .lk-content .btn-primary {
            background: var(--lk-primary);
            border-color: var(--lk-primary);
        }

        .lk-content .btn-primary:hover,
        .lk-content .btn-primary:focus {
            background: var(--lk-primary-hover);
            border-color: var(--lk-primary-hover);
        }

        .lk-content .btn-outline-primary {
            color: var(--lk-primary);
            border-color: var(--lk-primary);
        }

        .lk-content .btn-outline-primary:hover,
        .lk-content .btn-outline-primary:focus {
            background: var(--lk-primary);
            border-color: var(--lk-primary);
            color: #fff;
        }

        .lk-content .btn-success {
            background: var(--lk-success);
            border-color: var(--lk-success);
        }

        .lk-content .btn-danger {
            background: var(--lk-danger);
            border-color: var(--lk-danger);
        }

        .lk-content .card {
            border-color: var(--lk-border);
            border-radius: var(--lk-radius-card);
            box-shadow: var(--lk-shadow);
        }

        .lk-content .table thead th {
            background: var(--lk-surface-soft);
            border-bottom-color: var(--lk-border);
            color: var(--lk-text-label);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .lk-content .table tbody td {
            border-color: var(--lk-border-soft);
            color: var(--lk-text-body);
        }

        @media (max-width: 767.98px) {
            .lk-content {
                padding: 1rem;
            }

            .lk-page-title {
                align-items: flex-start;
                margin-bottom: 1.25rem;
            }
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