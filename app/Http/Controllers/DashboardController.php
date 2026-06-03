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

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = $this->dashboardMetrics();
        [$salesLabels, $salesData] = $this->monthlySales();

        $latestCustomers = Customer::withCount(['orders', 'wishlists'])->latest()->take(5)->get();
        $latestOrders = Order::with('customer')->latest()->take(5)->get();

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

        $customersWithOrders = Customer::has('orders')->count();
        $customersWithoutOrders = max($metrics['totalCustomers'] - $customersWithOrders, 0);

        $usersByCity = Customer::query()
            ->selectRaw("COALESCE(NULLIF(city, ''), 'Unknown') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->take(15)
            ->get();

        $usersByAudience = Customer::query()
            ->selectRaw("COALESCE(NULLIF(audience_type, ''), 'Not selected') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $orderStatusBreakdown = Order::query()
            ->selectRaw("COALESCE(NULLIF(status, ''), 'unknown') as label, COUNT(*) as total, COALESCE(SUM(total_amount), 0) as amount")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $paymentStatusBreakdown = Order::query()
            ->selectRaw("COALESCE(NULLIF(payment_status, ''), 'unknown') as label, COUNT(*) as total, COALESCE(SUM(total_amount), 0) as amount")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $paymentGatewayBreakdown = Order::query()
            ->selectRaw("COALESCE(NULLIF(payment_gateway, ''), 'Not selected') as label, COUNT(*) as total, COALESCE(SUM(total_amount), 0) as amount")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $recentCustomers = Customer::withCount(['orders', 'wishlists'])
            ->withSum('orders', 'total_amount')
            ->latest()
            ->take(15)
            ->get();

        $recentOrders = Order::with('customer')->latest()->take(15)->get();

        $recentInvoices = Order::with('customer')
            ->where('payment_status', 'paid')
            ->latest()
            ->take(15)
            ->get();

        $topSellingProducts = OrderItem::query()
            ->selectRaw('product_id, product_name, SUM(quantity) as total_quantity, COALESCE(SUM(subtotal), 0) as total_sales')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_quantity')
            ->take(10)
            ->get();

        $topWishlistProducts = Wishlist::with('product')
            ->select('product_id', DB::raw('COUNT(*) as total'))
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $lowStockProducts = Product::query()
            ->where('manage_stock', true)
            ->where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity')
            ->take(10)
            ->get();

        $returnStatusBreakdown = OrderReturn::query()
            ->selectRaw("COALESCE(NULLIF(status, ''), 'unknown') as label, COUNT(*) as total, COALESCE(SUM(refund_amount), 0) as amount")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $recentReturns = OrderReturn::with(['order', 'customer'])->latest()->take(10)->get();

        $reviewStatusBreakdown = [
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
        ];

        $cartReport = [
            'tracked' => false,
            'message' => 'Cart data is stored in visitor sessions only, so active add-to-cart reports are not available in the database.',
        ];

        return view('dashboard.analytics', array_merge($metrics, compact(
            'customersWithOrders',
            'customersWithoutOrders',
            'usersByCity',
            'usersByAudience',
            'orderStatusBreakdown',
            'paymentStatusBreakdown',
            'paymentGatewayBreakdown',
            'recentCustomers',
            'recentOrders',
            'recentInvoices',
            'topSellingProducts',
            'topWishlistProducts',
            'lowStockProducts',
            'returnStatusBreakdown',
            'recentReturns',
            'reviewStatusBreakdown',
            'cartReport',
            'salesLabels',
            'salesData'
        )));
    }

    public function exportAnalytics(Request $request)
    {
        $metrics = $this->dashboardMetrics();
        [$salesLabels, $salesData] = $this->monthlySales();

        $customersWithOrders = Customer::has('orders')->count();
        $customersWithoutOrders = max($metrics['totalCustomers'] - $customersWithOrders, 0);

        $usersByCity = Customer::query()
            ->selectRaw("COALESCE(NULLIF(city, ''), 'Unknown') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $usersByAudience = Customer::query()
            ->selectRaw("COALESCE(NULLIF(audience_type, ''), 'Not selected') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $orderStatusBreakdown = Order::query()
            ->selectRaw("COALESCE(NULLIF(status, ''), 'unknown') as label, COUNT(*) as total, COALESCE(SUM(total_amount), 0) as amount")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $paymentStatusBreakdown = Order::query()
            ->selectRaw("COALESCE(NULLIF(payment_status, ''), 'unknown') as label, COUNT(*) as total, COALESCE(SUM(total_amount), 0) as amount")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $paymentGatewayBreakdown = Order::query()
            ->selectRaw("COALESCE(NULLIF(payment_gateway, ''), 'Not selected') as label, COUNT(*) as total, COALESCE(SUM(total_amount), 0) as amount")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $customers = Customer::withCount(['orders', 'wishlists'])
            ->withSum('orders', 'total_amount')
            ->latest()
            ->get();

        $orders = Order::with('customer')->latest()->get();
        $invoices = Order::with('customer')->where('payment_status', 'paid')->latest()->get();

        $topSellingProducts = OrderItem::query()
            ->selectRaw('product_id, product_name, SUM(quantity) as total_quantity, COALESCE(SUM(subtotal), 0) as total_sales')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_quantity')
            ->get();

        $wishlistProducts = Wishlist::with('product')
            ->select('product_id', DB::raw('COUNT(*) as total'))
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->get();

        $wishlistDetails = Wishlist::with(['customer', 'product'])->latest()->get();

        $lowStockProducts = Product::query()
            ->where('manage_stock', true)
            ->where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity')
            ->get();

        $returns = OrderReturn::with(['order', 'customer', 'orderItem'])->latest()->get();

        $productReviews = ProductReview::with(['product', 'customer'])->latest()->get();
        $blogReviews = BlogReview::with(['blog', 'customer'])->latest()->get();

        $sheets = [
            'Summary' => [
                ['Metric', 'Value'],
                ['Generated At', now()->format('d M Y h:i A')],
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

    private function dashboardMetrics(): array
    {
        return Cache::remember('dashboard.metrics.v1', now()->addMinute(), function () {
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();
        $paidOrders = Order::where('payment_status', 'paid')->count();
        $pendingOrders = Order::whereIn('status', ['pending', 'processing', 'confirmed'])->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        $ordersLast30 = Order::where('created_at', '>=', now()->subDays(30))->count();
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->count();
        $totalCategories = Category::count();
        $wishlistCount = Wishlist::count();
        $wishlistCustomers = Wishlist::distinct('customer_id')->count('customer_id');
        $totalRevenue = (float) Order::where('payment_status', 'paid')->sum('total_amount');
        $last30DaysRevenue = (float) Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('total_amount');
        $newCustomersLast30 = Customer::where('created_at', '>=', now()->subDays(30))->count();
        $averageOrderValue = $paidOrders > 0 ? $totalRevenue / $paidOrders : 0;
        $paymentSuccessRate = $totalOrders > 0 ? round(($paidOrders / $totalOrders) * 100, 1) : 0;
        $invoiceCount = $paidOrders;
        $invoiceValue = $totalRevenue;
        $returnCount = OrderReturn::count();
        $productReviewCount = ProductReview::count();
        $blogReviewCount = BlogReview::count();

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
