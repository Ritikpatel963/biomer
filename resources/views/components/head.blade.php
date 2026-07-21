<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bharat Biomer Admin Dashboard</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/images/home-img/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/images/home-img/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/images/home-img/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/images/home-img/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/images/home-img/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/images/home-img/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/images/home-img/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/images/home-img/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/home-img/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/images/home-img/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/home-img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/images/home-img/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/home-img/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/home-img/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('assets/images/home-img/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('assets/images/home-img/ms-icon-144x144.png') }}">
    <meta name="msapplication-config" content="{{ asset('assets/images/home-img/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">
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
        $needsEditor = request()->routeIs('addBlog', 'editBlog', 'dashboard.pages.*');
        $needsMediaUi = request()->routeIs('gallery', 'carousel', 'videos');
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
    @stack('styles')
</head>
