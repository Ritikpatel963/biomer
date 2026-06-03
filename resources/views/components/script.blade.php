    <!-- jQuery library js -->
    <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
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
        $needsMediaUi = request()->routeIs('gallery', 'carousel', 'videos', 'dashboard.homepage-editor.*');
    @endphp
    @if($needsCharts)
        <script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script>
    @endif
    @if($needsDataTables)
        <script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>
    @endif
    @if($needsDatePicker)
        <script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>
    @endif
    @if($needsMediaUi)
        <script src="{{ asset('assets/js/lib/magnifc-popup.min.js') }}"></script>
        <script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>
        <script src="{{ asset('assets/js/lib/file-upload.js') }}"></script>
    @endif

    <!-- main js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof DataTable === 'undefined') return;

            document.querySelectorAll('.admin-data-table').forEach(function (table) {
                const firstBodyRow = table.querySelector('tbody tr');

                if (
                    table.dataset.dataTableInitialized === 'true' ||
                    !firstBodyRow ||
                    (table.querySelectorAll('tbody tr').length === 1 && firstBodyRow.querySelector('td[colspan]'))
                ) {
                    return;
                }

                new DataTable(table, {
                    responsive: true,
                    scrollX: false,
                    autoWidth: false,
                    pageLength: Number(table.dataset.pageLength || 10),
                    order: [],
                    columnDefs: [
                        {
                            orderable: false,
                            targets: table.dataset.noSortTargets
                                ? table.dataset.noSortTargets.split(',').map(Number)
                                : []
                        }
                    ]
                });

                table.dataset.dataTableInitialized = 'true';
            });
        });
    </script>

    <?php echo (isset($script) ? $script   : '')?>
    @stack('scripts')
