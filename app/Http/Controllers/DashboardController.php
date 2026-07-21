<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturn;
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\ProductReview;
use App\Models\BlogReview;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    private const ANALYTICS_EXPORT_ROW_LIMIT = 10000;

    public function index()
    {
        $metrics = $this->dashboardMetrics();
        [$salesLabels, $salesData] = $this->monthlySales();

        $latestCustomers = Customer::withCount(['orders', 'wishlists'])->latest()->take(5)->get();
        $latestOrders = Order::with(['customer', 'items'])->latest()->take(5)->get();

        return view('dashboard/index', array_merge($metrics, compact(
            'latestCustomers',
            'latestOrders',
            'salesLabels',
            'salesData'
        )));
    }

    public function analytics()
    {
        $metrics = $this->dashboardMetrics();
        [$salesLabels, $salesData] = $this->monthlySales();
        $analyticsData = $this->analyticsData($metrics);

        return view('dashboard.analytics', array_merge(
            $metrics,
            $analyticsData,
            compact('salesLabels', 'salesData')
        ));
    }

    public function exportAnalytics(Request $request)
    {
        $admin = $request->user('web');

        if (!$admin?->hasRole('super-admin')) {
            abort(403, 'Only super admins can export analytics reports.');
        }

        Log::info('Analytics export by admin: ' . $admin->id, [
            'admin_email' => $admin->email,
            'ip' => $request->ip(),
            'sections' => $request->input('sections', []),
            'row_limit' => self::ANALYTICS_EXPORT_ROW_LIMIT,
        ]);

        $metrics = $this->dashboardMetrics();
        [$salesLabels, $salesData] = $this->monthlySales();
        $analyticsData = $this->analyticsData($metrics, true);
        extract($analyticsData);

        $sheets = [
            'Summary' => [
                ['Metric', 'Value'],
                ['Generated At', now()->format('d M Y h:i A')],
                ['Detail Sheet Row Limit', self::ANALYTICS_EXPORT_ROW_LIMIT . ' latest rows per sheet'],
                ['Total Customers', $metrics['totalCustomers']],
                ['New Customers Last 30 Days', $metrics['newCustomersLast30']],
                ['Customers With Orders', $customersWithOrders],
                ['Customers Without Orders', $customersWithoutOrders],
                ['Total Orders', $metrics['totalOrders']],
                ['Orders Last 30 Days', $metrics['ordersLast30']],
                ['Paid Orders', $metrics['paidOrders']],
                ['Pending Orders', $metrics['pendingOrders']],
                ['Delivered Orders', $metrics['deliveredOrders']],
                ['Cancelled Orders', $metrics['cancelledOrders']],
                ['Total Revenue', $metrics['totalRevenue']],
                ['Revenue Last 30 Days', $metrics['last30DaysRevenue']],
                ['Average Order Value', $metrics['averageOrderValue']],
                ['Payment Success Rate %', $metrics['paymentSuccessRate']],
                ['Invoices', $metrics['invoiceCount']],
                ['Invoice Value', $metrics['invoiceValue']],
                ['Wishlist Adds', $metrics['wishlistCount']],
                ['Wishlist Customers', $metrics['wishlistCustomers']],
                ['Total Products', $metrics['totalProducts']],
                ['Active Products', $metrics['activeProducts']],
                ['Categories', $metrics['totalCategories']],
                ['Returns', $metrics['returnCount']],
                ['Product Reviews', $metrics['productReviewCount']],
                ['Blog Reviews', $metrics['blogReviewCount']],
            ],
            'Monthly Sales' => array_merge(
                [['Month', 'Paid Revenue']],
                collect($salesLabels)->map(fn ($label, $index) => [$label, $salesData[$index] ?? 0])->toArray()
            ),
            'Customers' => array_merge(
                [['Name', 'Email', 'Phone', 'Audience', 'City', 'State', 'Orders', 'Order Value', 'Wishlist', 'Joined']],
                $customers->map(fn ($customer) => [
                    $customer->name,
                    $customer->email,
                    $customer->phone,
                    $customer->audience_type,
                    $customer->city,
                    $customer->state,
                    $customer->orders_count,
                    $customer->orders_sum_total_amount ?? 0,
                    $customer->wishlists_count,
                    optional($customer->created_at)->format('d M Y'),
                ])->toArray()
            ),
            'Users By City' => array_merge(
                [['City', 'Users']],
                $usersByCity->map(fn ($row) => [$row->label, $row->total])->toArray()
            ),
            'Users By Audience' => array_merge(
                [['Audience', 'Users']],
                $usersByAudience->map(fn ($row) => [$row->label, $row->total])->toArray()
            ),
            'Orders' => array_merge(
                [['Order Number', 'Customer', 'Email', 'Phone', 'City', 'Status', 'Payment Status', 'Gateway', 'Subtotal', 'Shipping', 'Tax', 'Discount', 'Total', 'Date']],
                $orders->map(fn ($order) => [
                    $order->order_number,
                    $order->customer->name ?? $order->name,
                    $order->email,
                    $order->phone,
                    $order->city,
                    $order->status,
                    $order->payment_status,
                    $order->payment_gateway,
                    $order->net_amount ?? 0,
                    $order->shipping_amount ?? 0,
                    $order->tax_amount ?? 0,
                    $order->discount_amount ?? 0,
                    $order->total_amount ?? 0,
                    optional($order->created_at)->format('d M Y'),
                ])->toArray()
            ),
            'Invoices' => array_merge(
                [['Invoice Number', 'Customer', 'Payment Id', 'Gateway', 'Payment Status', 'Amount', 'Date']],
                $invoices->map(fn ($order) => [
                    $order->order_number,
                    $order->customer->name ?? $order->name,
                    $order->razorpay_payment_id ?? $order->cashfree_payment_id,
                    $order->payment_gateway,
                    $order->payment_status,
                    $order->total_amount ?? 0,
                    optional($order->created_at)->format('d M Y'),
                ])->toArray()
            ),
            'Order Status' => array_merge(
                [['Status', 'Orders', 'Value']],
                $orderStatusBreakdown->map(fn ($row) => [$row->label, $row->total, $row->amount])->toArray()
            ),
            'Payments' => array_merge(
                [['Type', 'Name', 'Orders', 'Value']],
                $paymentStatusBreakdown->map(fn ($row) => ['Status', $row->label, $row->total, $row->amount])
                    ->merge($paymentGatewayBreakdown->map(fn ($row) => ['Gateway', $row->label, $row->total, $row->amount]))
                    ->toArray()
            ),
            'Wishlist Summary' => array_merge(
                [['Product', 'Wishlist Adds']],
                $wishlistProducts->map(fn ($item) => [$item->product->name ?? 'Product #' . $item->product_id, $item->total])->toArray()
            ),
            'Wishlist Details' => array_merge(
                [['Customer', 'Email', 'Product', 'Added Date']],
                $wishlistDetails->map(fn ($item) => [
                    $item->customer->name ?? '-',
                    $item->customer->email ?? '-',
                    $item->product->name ?? 'Product #' . $item->product_id,
                    optional($item->created_at)->format('d M Y'),
                ])->toArray()
            ),
            'Products' => array_merge(
                [['Product', 'Sold Qty', 'Sales Value']],
                $topSellingProducts->map(fn ($product) => [
                    $product->product_name ?: 'Product #' . $product->product_id,
                    $product->total_quantity,
                    $product->total_sales,
                ])->toArray()
            ),
            'Low Stock' => array_merge(
                [['Product', 'SKU', 'Stock', 'Status']],
                $lowStockProducts->map(fn ($product) => [
                    $product->name,
                    $product->sku,
                    $product->stock_quantity,
                    $product->status,
                ])->toArray()
            ),
            'Returns' => array_merge(
                [['Order Number', 'Customer', 'Product', 'Status', 'Reason', 'Refund Amount', 'Requested Date']],
                $returns->map(fn ($return) => [
                    $return->order->order_number ?? '-',
                    $return->customer->name ?? '-',
                    $return->orderItem->product_name ?? '-',
                    $return->status,
                    $return->return_reason,
                    $return->refund_amount ?? 0,
                    optional($return->created_at)->format('d M Y'),
                ])->toArray()
            ),
            'Product Reviews' => array_merge(
                [['Product', 'Customer', 'Rating', 'Status', 'Review', 'Date']],
                $productReviews->map(fn ($review) => [
                    $review->product->name ?? '-',
                    $review->customer->name ?? '-',
                    $review->rating,
                    $review->status,
                    $review->review_text,
                    optional($review->created_at)->format('d M Y'),
                ])->toArray()
            ),
            'Blog Reviews' => array_merge(
                [['Blog', 'Customer', 'Name', 'Email', 'Rating', 'Status', 'Comment', 'Date']],
                $blogReviews->map(fn ($review) => [
                    $review->blog->title ?? '-',
                    $review->customer->name ?? '-',
                    $review->name,
                    $review->email,
                    $review->rating,
                    $review->status,
                    $review->comment,
                    optional($review->created_at)->format('d M Y'),
                ])->toArray()
            ),
            'Cart Tracking' => [
                ['Report', 'Status'],
                ['Add To Cart', 'Cart data is stored in visitor sessions only and is not available as a database report.'],
            ],
        ];

        $allowedSections = array_keys($sheets);
        $requestedSections = collect($request->input('sections', []))
            ->filter(fn ($section) => in_array($section, $allowedSections, true))
            ->values()
            ->all();

        if (!empty($requestedSections)) {
            $sheets = array_intersect_key($sheets, array_flip($requestedSections));
        }

        $filename = 'analytics-report-' . now()->format('Y-m-d-His') . '.xls';

        return response($this->buildExcelWorkbook($sheets), 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0, no-cache, must-revalidate',
            'Pragma' => 'public',
        ]);
    }

    private function analyticsData(array $metrics, bool $forExport = false): array
    {
        $exportLimit = $forExport ? self::ANALYTICS_EXPORT_ROW_LIMIT : null;
        $customersWithOrders = Customer::has('orders')->count();

        $data = [
            'customersWithOrders' => $customersWithOrders,
            'customersWithoutOrders' => max($metrics['totalCustomers'] - $customersWithOrders, 0),
            'usersByCity' => $this->limitQuery(
                Customer::query()
                    ->selectRaw("COALESCE(NULLIF(city, ''), 'Unknown') as label, COUNT(*) as total")
                    ->groupBy('label')
                    ->orderByDesc('total'),
                $forExport ? $exportLimit : 15
            )->get(),
            'usersByAudience' => $this->limitQuery(
                Customer::query()
                    ->selectRaw("COALESCE(NULLIF(audience_type, ''), 'Not selected') as label, COUNT(*) as total")
                    ->groupBy('label')
                    ->orderByDesc('total'),
                $exportLimit
            )->get(),
            'orderStatusBreakdown' => $this->limitQuery(
                Order::query()
                    ->selectRaw("COALESCE(NULLIF(status, ''), 'unknown') as label, COUNT(*) as total, COALESCE(SUM(total_amount), 0) as amount")
                    ->groupBy('label')
                    ->orderByDesc('total'),
                $exportLimit
            )->get(),
            'paymentStatusBreakdown' => $this->limitQuery(
                Order::query()
                    ->selectRaw("COALESCE(NULLIF(payment_status, ''), 'unknown') as label, COUNT(*) as total, COALESCE(SUM(total_amount), 0) as amount")
                    ->groupBy('label')
                    ->orderByDesc('total'),
                $exportLimit
            )->get(),
            'paymentGatewayBreakdown' => $this->limitQuery(
                Order::query()
                    ->selectRaw("COALESCE(NULLIF(payment_gateway, ''), 'Not selected') as label, COUNT(*) as total, COALESCE(SUM(total_amount), 0) as amount")
                    ->groupBy('label')
                    ->orderByDesc('total'),
                $exportLimit
            )->get(),
            'topSellingProducts' => $this->limitQuery(
                OrderItem::query()
                    ->selectRaw('product_id, product_name, SUM(quantity) as total_quantity, COALESCE(SUM(subtotal), 0) as total_sales')
                    ->groupBy('product_id', 'product_name')
                    ->orderByDesc('total_quantity'),
                $forExport ? $exportLimit : 10
            )->get(),
            'lowStockProducts' => $this->limitQuery(
                Product::query()
                    ->where('manage_stock', true)
                    ->where('stock_quantity', '<=', 5)
                    ->orderBy('stock_quantity'),
                $forExport ? $exportLimit : 10
            )->get(),
            'returnStatusBreakdown' => OrderReturn::query()
                ->selectRaw("COALESCE(NULLIF(status, ''), 'unknown') as label, COUNT(*) as total, COALESCE(SUM(refund_amount), 0) as amount")
                ->groupBy('label')
                ->orderByDesc('total')
                ->get(),
            'reviewStatusBreakdown' => [
                'product' => ProductReview::query()
                    ->selectRaw("COALESCE(NULLIF(status, ''), 'unknown') as label, COUNT(*) as total")
                    ->groupBy('label')
                    ->orderByDesc('total')
                    ->get(),
                'blog' => BlogReview::query()
                    ->selectRaw("COALESCE(NULLIF(status, ''), 'unknown') as label, COUNT(*) as total")
                    ->groupBy('label')
                    ->orderByDesc('total')
                    ->get(),
            ],
            'cartReport' => [
                'tracked' => false,
                'message' => 'Cart data is stored in visitor sessions only, so active add-to-cart reports are not available in the database.',
            ],
        ];

        if (!$forExport) {
            return array_merge($data, [
                'recentCustomers' => Customer::withCount(['orders', 'wishlists'])
                    ->withSum('orders', 'total_amount')
                    ->latest()
                    ->take(15)
                    ->get(),
                'recentOrders' => Order::with('customer')->latest()->take(15)->get(),
                'recentInvoices' => Order::with('customer')
                    ->where('payment_status', 'paid')
                    ->latest()
                    ->take(15)
                    ->get(),
                'topWishlistProducts' => Wishlist::with('product')
                    ->select('product_id', DB::raw('COUNT(*) as total'))
                    ->groupBy('product_id')
                    ->orderByDesc('total')
                    ->take(10)
                    ->get(),
                'recentReturns' => OrderReturn::with(['order', 'customer'])->latest()->take(10)->get(),
            ]);
        }

        return array_merge($data, [
            'customers' => Customer::withCount(['orders', 'wishlists'])
                ->withSum('orders', 'total_amount')
                ->latest()
                ->limit(self::ANALYTICS_EXPORT_ROW_LIMIT)
                ->get(),
            'orders' => Order::with('customer')->latest()->limit(self::ANALYTICS_EXPORT_ROW_LIMIT)->get(),
            'invoices' => Order::with('customer')->where('payment_status', 'paid')->latest()->limit(self::ANALYTICS_EXPORT_ROW_LIMIT)->get(),
            'wishlistProducts' => Wishlist::with('product')
                ->select('product_id', DB::raw('COUNT(*) as total'))
                ->groupBy('product_id')
                ->orderByDesc('total')
                ->limit(self::ANALYTICS_EXPORT_ROW_LIMIT)
                ->get(),
            'wishlistDetails' => Wishlist::with(['customer', 'product'])->latest()->limit(self::ANALYTICS_EXPORT_ROW_LIMIT)->get(),
            'returns' => OrderReturn::with(['order', 'customer', 'orderItem'])->latest()->limit(self::ANALYTICS_EXPORT_ROW_LIMIT)->get(),
            'productReviews' => ProductReview::with(['product', 'customer'])->latest()->limit(self::ANALYTICS_EXPORT_ROW_LIMIT)->get(),
            'blogReviews' => BlogReview::with(['blog', 'customer'])->latest()->limit(self::ANALYTICS_EXPORT_ROW_LIMIT)->get(),
        ]);
    }

    private function limitQuery($query, ?int $limit)
    {
        return $limit ? $query->limit($limit) : $query;
    }

    private function dashboardMetrics(): array
    {
        return Cache::remember('dashboard.metrics.v1', now()->addMinute(), function () {
        $last30Days = now()->subDays(30);

        $orderMetrics = Order::query()
            ->selectRaw("
                COUNT(*) as total_orders,
                SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid_orders,
                SUM(CASE WHEN status IN ('pending', 'processing', 'confirmed') THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as orders_last_30,
                COALESCE(SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END), 0) as total_revenue,
                COALESCE(SUM(CASE WHEN payment_status = 'paid' AND created_at >= ? THEN total_amount ELSE 0 END), 0) as last_30_days_revenue
            ", [$last30Days, $last30Days])
            ->first();

        $customerMetrics = Customer::query()
            ->selectRaw(
                "COUNT(*) as total_customers,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as new_customers_last_30",
                [$last30Days]
            )
            ->first();

        $productMetrics = Product::query()
            ->selectRaw("
                COUNT(*) as total_products,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_products
            ")
            ->first();

        $wishlistMetrics = Wishlist::query()
            ->selectRaw('COUNT(*) as wishlist_count, COUNT(DISTINCT customer_id) as wishlist_customers')
            ->first();

        $miscCounts = collect(DB::select("
            SELECT 'total_categories' as metric, COUNT(*) as total FROM categories
            UNION ALL
            SELECT 'return_count' as metric, COUNT(*) as total FROM order_returns
            UNION ALL
            SELECT 'product_review_count' as metric, COUNT(*) as total FROM product_reviews
            UNION ALL
            SELECT 'blog_review_count' as metric, COUNT(*) as total FROM blog_reviews
        "))->pluck('total', 'metric');

        $totalCustomers = (int) ($customerMetrics->total_customers ?? 0);
        $newCustomersLast30 = (int) ($customerMetrics->new_customers_last_30 ?? 0);
        $totalOrders = (int) ($orderMetrics->total_orders ?? 0);
        $paidOrders = (int) ($orderMetrics->paid_orders ?? 0);
        $pendingOrders = (int) ($orderMetrics->pending_orders ?? 0);
        $deliveredOrders = (int) ($orderMetrics->delivered_orders ?? 0);
        $cancelledOrders = (int) ($orderMetrics->cancelled_orders ?? 0);
        $ordersLast30 = (int) ($orderMetrics->orders_last_30 ?? 0);
        $totalRevenue = (float) ($orderMetrics->total_revenue ?? 0);
        $last30DaysRevenue = (float) ($orderMetrics->last_30_days_revenue ?? 0);
        $totalProducts = (int) ($productMetrics->total_products ?? 0);
        $activeProducts = (int) ($productMetrics->active_products ?? 0);
        $totalCategories = (int) ($miscCounts['total_categories'] ?? 0);
        $wishlistCount = (int) ($wishlistMetrics->wishlist_count ?? 0);
        $wishlistCustomers = (int) ($wishlistMetrics->wishlist_customers ?? 0);
        $averageOrderValue = $paidOrders > 0 ? $totalRevenue / $paidOrders : 0;
        $paymentSuccessRate = $totalOrders > 0 ? round(($paidOrders / $totalOrders) * 100, 1) : 0;
        $invoiceCount = $paidOrders;
        $invoiceValue = $totalRevenue;
        $returnCount = (int) ($miscCounts['return_count'] ?? 0);
        $productReviewCount = (int) ($miscCounts['product_review_count'] ?? 0);
        $blogReviewCount = (int) ($miscCounts['blog_review_count'] ?? 0);

        return compact(
            'totalCustomers',
            'totalOrders',
            'paidOrders',
            'pendingOrders',
            'deliveredOrders',
            'cancelledOrders',
            'ordersLast30',
            'totalProducts',
            'activeProducts',
            'totalCategories',
            'wishlistCount',
            'wishlistCustomers',
            'totalRevenue',
            'last30DaysRevenue',
            'newCustomersLast30',
            'averageOrderValue',
            'paymentSuccessRate',
            'invoiceCount',
            'invoiceValue',
            'returnCount',
            'productReviewCount',
            'blogReviewCount'
        );
        });
    }

    private function monthlySales(): array
    {
        return Cache::remember('dashboard.monthly-sales.v1', now()->addMinutes(5), function () {
        $salesLabels = collect(range(11, 0, -1))
            ->map(fn ($monthsAgo) => now()->subMonths($monthsAgo)->format('M Y'))
            ->toArray();

        $salesDataByMonth = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw("YEAR(created_at) as year, MONTH(created_at) as month, DATE_FORMAT(created_at, '%b %Y') as month_label, SUM(total_amount) as total")
            ->groupBy('year', 'month', 'month_label')
            ->orderBy('year')
            ->orderBy('month')
            ->pluck('total', 'month_label')
            ->toArray();

        $salesData = collect($salesLabels)
            ->map(fn ($label) => round($salesDataByMonth[$label] ?? 0, 2))
            ->toArray();

        return [$salesLabels, $salesData];
        });
    }

    private function buildExcelWorkbook(array $sheets): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ';
        $xml .= 'xmlns:o="urn:schemas-microsoft-com:office:office" ';
        $xml .= 'xmlns:x="urn:schemas-microsoft-com:office:excel" ';
        $xml .= 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";
        $xml .= '<Styles>';
        $xml .= '<Style ss:ID="Header"><Font ss:Bold="1" ss:Color="#FFFFFF"/><Interior ss:Color="#2563EB" ss:Pattern="Solid"/></Style>';
        $xml .= '<Style ss:ID="Text"><Alignment ss:Vertical="Center"/></Style>';
        $xml .= '</Styles>' . "\n";

        foreach ($sheets as $name => $rows) {
            $xml .= '<Worksheet ss:Name="' . $this->xmlEscape($this->sheetName($name)) . '"><Table>' . "\n";

            foreach ($rows as $index => $row) {
                $xml .= '<Row>';
                foreach ($row as $value) {
                    $style = $index === 0 ? ' ss:StyleID="Header"' : ' ss:StyleID="Text"';
                    $type = is_numeric($value) && $value !== '' ? 'Number' : 'String';
                    $xml .= '<Cell' . $style . '><Data ss:Type="' . $type . '">' . $this->xmlEscape((string) ($value ?? '')) . '</Data></Cell>';
                }
                $xml .= '</Row>' . "\n";
            }

            $xml .= '</Table></Worksheet>' . "\n";
        }

        $xml .= '</Workbook>';

        return $xml;
    }

    private function sheetName(string $name): string
    {
        $name = preg_replace('/[\\\\\\/\\?\\*\\[\\]\\:]/', ' ', $name);

        return mb_substr(trim($name), 0, 31) ?: 'Report';
    }

    private function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
