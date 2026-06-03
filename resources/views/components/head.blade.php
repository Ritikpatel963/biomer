<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bharat Biomer Admin Dashboard</title>
    <link rel="icon" type="image/png"  href="{{ asset('assets/images/favicon.png') }}" sizes="16x16">
    <!-- remix icon font css  -->
    <link rel="stylesheet"  href="{{ asset('assets/css/remixicon.css') }}">
    <!-- BootStrap css -->
    <link rel="stylesheet"  href="{{ asset('assets/css/lib/bootstrap.min.css') }}">
    @php
        $needsCharts = request()->routeIs('dashboard', 'index', 'dashboard.analytics', 'columnChart', 'lineChart', 'pieChart');
        $needsDataTables = request()->routeIs(
            'dashboard.*.index',
            'blog',
            'usersList',
            'tableData',
            'invoiceList',
            'dashboard.invoices.index'
        );
        $needsDatePicker = request()->routeIs('calendar', 'form*', 'dashboard.orders.*', 'dashboard.analytics');
        $needsEditor = request()->routeIs('addBlog', 'editBlog', 'dashboard.pages.*', 'dashboard.homepage-editor.*');
        $needsMediaUi = request()->routeIs('gallery', 'carousel', 'videos', 'dashboard.homepage-editor.*');
    @endphp
    @if($needsCharts)
        <link rel="stylesheet" href="{{ asset('assets/css/lib/apexcharts.css') }}">
    @endif
    @if($needsDataTables)
        <link rel="stylesheet" href="{{ asset('assets/css/lib/dataTables.min.css') }}">
    @endif
    @if($needsEditor)
        <link rel="stylesheet" href="{{ asset('assets/css/lib/editor-katex.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/lib/editor.atom-one-dark.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/lib/editor.quill.snow.css') }}">
    @endif
    @if($needsDatePicker)
        <link rel="stylesheet" href="{{ asset('assets/css/lib/flatpickr.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/lib/full-calendar.css') }}">
    @endif
    @if($needsMediaUi)
        <link rel="stylesheet" href="{{ asset('assets/css/lib/magnific-popup.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/lib/slick.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/lib/file-upload.css') }}">
    @endif
    <!-- main css -->
    <link rel="stylesheet"  href="{{ asset('assets/css/style.css') }}">
    <style>
        :root {
            --admin-brand: #0f766e;
            --admin-brand-dark: #0f172a;
            --admin-brand-soft: #ecfdf5;
            --admin-brand-border: #99f6e4;
            --admin-accent: #0f766e;
            --admin-accent-soft: #ecfdf5;
            --admin-warning: #d97706;
            --admin-danger: #dc2626;
            --admin-sidebar-gradient: linear-gradient(180deg, #0f172a 0%, #111827 58%, #0f766e 130%);
            --admin-button-gradient: linear-gradient(135deg, #0f172a 0%, #111827 42%, #0f766e 130%);
            --admin-surface: #ffffff;
            --admin-surface-soft: #f8fafc;
            --admin-border: #e2e8f0;
            --admin-text-dark: #0f172a;
            --admin-text-muted: #64748b;
        }

        .dashboard-main-body {
            padding-bottom: 28px;
            color: #1e293b;
            background: #f6f8fb;
        }

        .admin-layout-card {
            background: var(--admin-surface);
            border: 1px solid var(--admin-border);
            border-radius: 24px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.07);
            overflow: hidden;
        }

        .admin-layout-card__body {
            padding: 24px;
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, 0.06), transparent 28%),
                linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        .admin-shell {
            display: block;
        }

        .admin-page-card {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid var(--admin-border);
            border-radius: 24px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
            padding: 24px;
        }

        .admin-page-card__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .admin-page-card__eyebrow {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--admin-brand);
            margin-bottom: 10px;
        }

        .admin-page-card__title {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            color: var(--admin-text-dark);
        }

        .admin-page-card__desc {
            margin: 10px 0 0;
            max-width: 820px;
            color: var(--admin-text-muted);
            line-height: 1.7;
        }

        .admin-page-card__actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .admin-toolbar-tabs {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #edf1f7;
        }

        .admin-toolbar-tabs__link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 999px;
            text-decoration: none;
            color: #475467;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            font-weight: 600;
            transition: all .2s ease;
        }

        .admin-toolbar-tabs__link.active,
        .admin-toolbar-tabs__link:hover {
            background: var(--admin-brand);
            border-color: var(--admin-brand);
            color: #ffffff;
        }

        .admin-section-card {
            background: var(--admin-surface);
            border: 1px solid var(--admin-border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
        }

        .admin-section-card__header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--admin-border);
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        .admin-section-card__body {
            padding: 22px;
        }

        .admin-nested-card {
            border: 1px solid var(--admin-border);
            border-radius: 16px;
            padding: 16px;
            background: var(--admin-surface-soft);
        }

        .admin-logo-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 92px;
            border: 1px dashed var(--admin-brand-border);
            border-radius: 16px;
            background: var(--admin-surface-soft);
            padding: 12px;
        }

        .admin-logo-preview img {
            max-width: 100%;
            max-height: 60px;
            object-fit: contain;
        }

        .admin-info-chip {
            border: 1px solid var(--admin-brand-border);
            background: var(--admin-brand-soft);
            border-radius: 18px;
            padding: 16px 18px;
            min-height: 100%;
        }

        .admin-info-chip__label {
            display: block;
            color: #667085;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: 8px;
        }

        .dashboard-main-body .card {
            border: 1px solid var(--admin-border);
            border-radius: 16px;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            background: var(--admin-surface);
        }

        .dashboard-main-body .card-header {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-bottom: 1px solid var(--admin-border);
            padding: 16px 20px;
        }

        .dashboard-main-body .card-title,
        .dashboard-main-body .card-header h5,
        .dashboard-main-body .card-header h6 {
            font-size: 16px;
            font-weight: 800;
            color: var(--admin-text-dark) !important;
            letter-spacing: 0;
        }

        .dashboard-main-body .card-body {
            padding: 20px;
        }

        .dashboard-main-body .bg-gradient-start-1,
        .dashboard-main-body .bg-gradient-start-2,
        .dashboard-main-body .bg-gradient-start-3,
        .dashboard-main-body .bg-gradient-start-4,
        .dashboard-main-body .bg-gradient-start-5 {
            background: #ffffff !important;
        }

        .dashboard-main-body .btn-primary,
        .dashboard-main-body .btn-primary-600,
        .dashboard-main-body .bg-primary-600 {
            background: var(--admin-brand) !important;
            border-color: var(--admin-brand) !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            white-space: nowrap;
            line-height: 1.2;
            border-radius: 8px;
            font-weight: 700;
            min-height: 34px;
            border-width: 1px;
            transition: background-color .18s ease, border-color .18s ease, color .18s ease, box-shadow .18s ease, transform .18s ease;
        }

        .dashboard-main-body .btn:hover {
            transform: translateY(-1px);
        }

        .dashboard-main-body .btn:focus,
        .dashboard-main-body .btn:focus-visible {
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, 0.18) !important;
            outline: none;
        }

        .dashboard-main-body .btn:disabled,
        .dashboard-main-body .btn.disabled {
            background: #e2e8f0 !important;
            border-color: #e2e8f0 !important;
            color: #94a3b8 !important;
            opacity: 1;
            transform: none;
        }

        .dashboard-main-body .btn-primary,
        .dashboard-main-body .btn-primary-600,
        .dashboard-main-body .btn.bg-primary-600 {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-primary:hover,
        .dashboard-main-body .btn-primary:focus,
        .dashboard-main-body .btn-primary-600:hover,
        .dashboard-main-body .btn-primary-600:focus,
        .dashboard-main-body .btn.bg-primary-600:hover {
            background: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-outline-primary {
            background: #ffffff !important;
            border-color: #2563eb !important;
            color: #1d4ed8 !important;
        }

        .dashboard-main-body .btn-outline-primary:hover,
        .dashboard-main-body .btn-outline-primary:focus {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-success,
        .dashboard-main-body .btn-success-600,
        .dashboard-main-body .btn-outline-success:hover,
        .dashboard-main-body .btn-outline-success:focus {
            background: #0f766e !important;
            border-color: #0f766e !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-success:hover,
        .dashboard-main-body .btn-success:focus,
        .dashboard-main-body .btn-success-600:hover,
        .dashboard-main-body .btn-success-600:focus {
            background: #115e59 !important;
            border-color: #115e59 !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-outline-success {
            background: #ffffff !important;
            border-color: #0f766e !important;
            color: #0f766e !important;
        }

        .dashboard-main-body .btn-danger,
        .dashboard-main-body .btn-danger-600,
        .dashboard-main-body .btn-outline-danger:hover,
        .dashboard-main-body .btn-outline-danger:focus {
            background: #dc2626 !important;
            border-color: #dc2626 !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-danger:hover,
        .dashboard-main-body .btn-danger:focus,
        .dashboard-main-body .btn-danger-600:hover,
        .dashboard-main-body .btn-danger-600:focus {
            background: #b91c1c !important;
            border-color: #b91c1c !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-outline-danger {
            background: #ffffff !important;
            border-color: #dc2626 !important;
            color: #dc2626 !important;
        }

        .dashboard-main-body .btn-warning,
        .dashboard-main-body .btn-warning-600,
        .dashboard-main-body .btn-outline-warning:hover,
        .dashboard-main-body .btn-outline-warning:focus {
            background: #d97706 !important;
            border-color: #d97706 !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-warning:hover,
        .dashboard-main-body .btn-warning:focus,
        .dashboard-main-body .btn-warning-600:hover,
        .dashboard-main-body .btn-warning-600:focus {
            background: #b45309 !important;
            border-color: #b45309 !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-outline-warning {
            background: #ffffff !important;
            border-color: #d97706 !important;
            color: #b45309 !important;
        }

        .dashboard-main-body .btn-info,
        .dashboard-main-body .btn-info-600,
        .dashboard-main-body .btn-outline-info:hover,
        .dashboard-main-body .btn-outline-info:focus {
            background: #0891b2 !important;
            border-color: #0891b2 !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-info:hover,
        .dashboard-main-body .btn-info:focus,
        .dashboard-main-body .btn-info-600:hover,
        .dashboard-main-body .btn-info-600:focus {
            background: #0e7490 !important;
            border-color: #0e7490 !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-outline-info {
            background: #ffffff !important;
            border-color: #0891b2 !important;
            color: #0e7490 !important;
        }

        .dashboard-main-body .btn-secondary,
        .dashboard-main-body .btn-outline-secondary:hover,
        .dashboard-main-body .btn-outline-secondary:focus {
            background: #475569 !important;
            border-color: #475569 !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-secondary:hover,
        .dashboard-main-body .btn-secondary:focus {
            background: #334155 !important;
            border-color: #334155 !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-outline-secondary {
            background: #ffffff !important;
            border-color: #cbd5e1 !important;
            color: #334155 !important;
        }

        .dashboard-main-body .btn-light,
        .dashboard-main-body .btn-outline-light {
            background: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #334155 !important;
        }

        .dashboard-main-body .btn-light:hover,
        .dashboard-main-body .btn-outline-light:hover {
            background: #e2e8f0 !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }

        .dashboard-main-body a.btn,
        .dashboard-main-body button.btn {
            text-decoration: none !important;
        }

        .dashboard-main-body .row > .col > .card,
        .dashboard-main-body .row > [class*="col-"] > .card {
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .dashboard-main-body .row > .col > .card:hover,
        .dashboard-main-body .row > [class*="col-"] > .card:hover {
            transform: translateY(-2px);
            border-color: var(--admin-brand-border);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.09);
        }

        .dashboard-main-body .table-responsive {
            overflow-x: auto;
        }

        .dashboard-main-body .basic-data-table,
        .dashboard-main-body .card:has(table) {
            border-radius: 16px;
        }

        .dashboard-main-body table.dataTable {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0 !important;
        }

        .dashboard-main-body table.dataTable thead th,
        .dashboard-main-body .table thead th {
            background: #f8fafc !important;
            border-bottom: 1px solid var(--admin-border) !important;
            color: #334155;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
            padding: 14px 16px !important;
            vertical-align: middle;
        }

        .dashboard-main-body table.dataTable tbody td,
        .dashboard-main-body .table tbody td {
            border-bottom: 1px solid #eef2f7;
            color: #334155;
            padding: 14px 16px !important;
            vertical-align: middle;
        }

        .dashboard-main-body table.dataTable tbody tr:last-child td,
        .dashboard-main-body .table tbody tr:last-child td {
            border-bottom: 0;
        }

        .dashboard-main-body table.dataTable tbody tr:hover td,
        .dashboard-main-body .table-hover tbody tr:hover td {
            background: #f8fafc;
        }

        .dashboard-main-body div.dt-container {
            padding: 18px 18px 14px;
        }

        .dashboard-main-body div.dt-container .dt-layout-row:first-child {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 14px;
        }

        .dashboard-main-body div.dt-container .dt-layout-row:last-child {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-top: 14px;
        }

        .dashboard-main-body div.dt-container .dt-layout-cell {
            display: block;
            padding: 0;
        }

        .dashboard-main-body div.dt-container .dt-search,
        .dashboard-main-body div.dt-container .dt-length,
        .dashboard-main-body div.dt-container .dt-info {
            font-size: 13px;
            color: #667085;
            font-weight: 600;
        }

        .dashboard-main-body div.dt-container .dt-search input,
        .dashboard-main-body div.dt-container .dt-input {
            border: 1px solid var(--admin-border);
            border-radius: 10px;
            min-height: 38px;
            padding: 8px 12px;
            background: #ffffff;
            color: #0f172a;
            outline: none;
            box-shadow: none;
        }

        .dashboard-main-body div.dt-container .dt-search input:focus,
        .dashboard-main-body div.dt-container .dt-input:focus {
            border-color: var(--admin-brand);
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, 0.12);
        }

        .dashboard-main-body div.dt-container .dt-paging {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .dashboard-main-body div.dt-container .dt-paging .dt-paging-button {
            min-width: 34px;
            min-height: 34px;
            padding: 7px 11px;
            border: 1px solid var(--admin-border) !important;
            border-radius: 9px;
            background: #ffffff !important;
            color: #334155 !important;
            font-weight: 700;
            box-shadow: none !important;
        }

        .dashboard-main-body div.dt-container .dt-paging .dt-paging-button.current,
        .dashboard-main-body div.dt-container .dt-paging .dt-paging-button:hover {
            background: var(--admin-brand) !important;
            border-color: var(--admin-brand) !important;
            color: #ffffff !important;
        }

        .dashboard-main-body div.dt-container .dt-paging .dt-paging-button.disabled {
            opacity: .45;
            color: #94a3b8 !important;
            background: #f8fafc !important;
        }

        .dashboard-main-body .badge,
        .dashboard-main-body .rounded-pill {
            font-weight: 800;
            letter-spacing: 0;
        }

        .dashboard-main-body code {
            color: #1d4ed8;
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 6px;
            padding: 2px 6px;
            font-size: 12px;
        }

        .dashboard-main-body .btn-primary:hover,
        .dashboard-main-body .btn-primary:focus,
        .dashboard-main-body .btn-primary-600:hover,
        .dashboard-main-body .btn-primary-600:focus {
            background: var(--admin-sidebar-gradient) !important;
            border-color: #0f766e !important;
            color: #ffffff !important;
        }

        .dashboard-main-body .btn:not(.btn-outline-primary):not(.btn-outline-secondary):not(.btn-outline-danger):not(.btn-light):not(.btn-link),
        .dashboard-main-body .btn-primary,
        .dashboard-main-body .btn-primary-600,
        .dashboard-main-body .btn-success,
        .dashboard-main-body .btn-info,
        .dashboard-main-body .btn-warning,
        .dashboard-main-body .btn-danger,
        .dashboard-main-body .bg-primary-600 {
            background: var(--admin-button-gradient) !important;
            border-color: #0f766e !important;
            color: #ffffff !important;
            box-shadow: 0 10px 24px rgba(15, 118, 110, 0.18);
        }

        .dashboard-main-body .btn:hover,
        .dashboard-main-body .btn:focus {
            filter: brightness(1.08);
            color: #ffffff !important;
        }

        .dashboard-main-body .btn-outline-primary,
        .dashboard-main-body .border-primary-600 {
            border-color: var(--admin-brand) !important;
        }

        .dashboard-main-body .text-primary-600,
        .dashboard-main-body .text-primary-main,
        .dashboard-main-body .text-primary-light,
        .dashboard-main-body .card-title,
        .dashboard-main-body a:hover {
            color: var(--admin-brand) !important;
        }

        .dashboard-main-body .bg-primary-100,
        .dashboard-main-body .bg-success-focus {
            background: var(--admin-accent-soft) !important;
        }

        .dashboard-main-body .text-success-main,
        .dashboard-main-body .text-success-600,
        .dashboard-main-body .text-success-700 {
            color: var(--admin-accent) !important;
        }

        .dashboard-main-body .bg-warning-focus {
            background: #fffbeb !important;
        }

        .dashboard-main-body .text-warning-main,
        .dashboard-main-body .text-warning-600 {
            color: var(--admin-warning) !important;
        }

        .dashboard-main-body .bg-danger-focus {
            background: #fef2f2 !important;
        }

        .dashboard-main-body .text-danger-main,
        .dashboard-main-body .text-danger-600 {
            color: var(--admin-danger) !important;
        }

        .dashboard-main-body .form-control:focus,
        .dashboard-main-body .form-select:focus {
            border-color: var(--admin-brand-border);
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, 0.14);
        }

        .sidebar {
            background: var(--admin-sidebar-gradient);
            border-right: 1px solid rgba(226, 232, 240, 0.08);
        }

        .sidebar .menu-icon,
        .sidebar .sidebar-menu a i,
        .sidebar .sidebar-menu a span {
            color: #cbd5e1;
        }

        .sidebar .sidebar-menu > li > a,
        .sidebar .sidebar-submenu li a {
            border-radius: 14px;
        }

        .sidebar .sidebar-submenu {
            margin: 6px 0 10px;
            padding: 0 0 0 34px !important;
        }

        .sidebar .sidebar-submenu li {
            margin: 2px 0;
        }

        .sidebar .sidebar-submenu li a {
            align-items: center;
            background: transparent !important;
            border-radius: 10px;
            color: #cbd5e1 !important;
            display: flex;
            font-size: 14px;
            min-height: 34px;
            padding: 8px 12px !important;
        }

        .sidebar .sidebar-submenu li a .circle-icon {
            display: none !important;
        }

        .sidebar .sidebar-menu > li.active-page > a,
        .sidebar .sidebar-menu > li > a:hover,
        .sidebar .sidebar-submenu li a:hover {
            background: rgba(37, 99, 235, 0.16);
        }

        .sidebar .sidebar-submenu li.active-page a,
        .sidebar .sidebar-submenu li a:hover {
            background: rgba(148, 163, 184, 0.14) !important;
            color: #ffffff !important;
        }

        .sidebar .sidebar-menu > li.active-page > a span,
        .sidebar .sidebar-menu > li.active-page > a i,
        .sidebar .sidebar-menu > li > a:hover span,
        .sidebar .sidebar-menu > li > a:hover i {
            color: #ffffff;
        }

        .sidebar .circle-icon {
            color: #38bdf8 !important;
        }

        .sidebar-close-btn i {
            font-size: 20px;
            color: #ffffff;
        }

        .dashboard-main-body .admin-action-iconized {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .dashboard-main-body .admin-action-iconized i {
            font-size: 15px;
            line-height: 1;
        }

        .dashboard-main-body {
            background: #f8faf9;
        }

        .dashboard-main-body > .card,
        .dashboard-main-body .card,
        .dashboard-main-body .basic-data-table,
        .dashboard-main-body .form-card {
            border: 1px solid #dfe7ee !important;
            border-radius: 14px !important;
            box-shadow: 0 14px 30px rgba(15, 23, 42, .05) !important;
            overflow: hidden;
        }

        .dashboard-main-body .card-header,
        .dashboard-main-body .form-section-title {
            background: #ffffff !important;
            border-bottom: 1px solid #edf2f7 !important;
            color: #111827 !important;
            font-size: 15px;
            font-weight: 800;
        }

        .dashboard-main-body .card-header {
            padding: 17px 20px !important;
        }

        .dashboard-main-body .card-body,
        .dashboard-main-body .form-section {
            padding: 22px !important;
        }

        .dashboard-main-body .form-label,
        .dashboard-main-body label {
            color: #334155;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .dashboard-main-body .form-control,
        .dashboard-main-body .form-select,
        .dashboard-main-body textarea,
        .dashboard-main-body input[type="text"],
        .dashboard-main-body input[type="email"],
        .dashboard-main-body input[type="number"],
        .dashboard-main-body input[type="url"],
        .dashboard-main-body input[type="date"],
        .dashboard-main-body input[type="datetime-local"],
        .dashboard-main-body select {
            border-color: #d7e1e8 !important;
            border-radius: 10px !important;
            box-shadow: none !important;
            color: #111827;
            min-height: 42px;
        }

        .dashboard-main-body textarea {
            min-height: 110px;
        }

        .dashboard-main-body .table-responsive {
            border-radius: 12px;
        }

        .dashboard-main-body table.table thead th {
            background: #f8fafc !important;
            color: #334155 !important;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .dashboard-main-body table.table tbody td {
            color: #334155;
            vertical-align: middle;
        }

        .dashboard-main-body .modal-content {
            border: 1px solid #dfe7ee;
            border-radius: 14px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, .18);
        }

        .dashboard-main-body .modal-header,
        .dashboard-main-body .modal-footer {
            border-color: #edf2f7;
        }

        @media (max-width: 767.98px) {
            .admin-layout-card__body {
                padding: 16px;
            }

            .admin-page-card {
                padding: 18px;
                border-radius: 18px;
            }

            .admin-page-card__title {
                font-size: 24px;
            }

            .admin-section-card__body,
            .admin-section-card__header {
                padding: 16px;
            }
        }
    </style>
    @stack('styles')
</head>
