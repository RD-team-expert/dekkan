<?php

namespace App\Filament\Resources\OverviewResource\Widgets;

use App\Models\PaymentReceipt;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Users
        $totalUsers = User::count();

        // Total Sales Amount
        $totalSales = Sale::sum('total_price');
        $salesTrend = Sale::whereDate('date_time', '>=', now()->subDays(30))->sum('total_price') >= $totalSales * 0.5
            ? 'increase'
            : 'decrease';

        // Total Purchases Amount
        $totalPurchases = Purchase::sum('purchase_price');
        $purchasesTrend = Purchase::whereDate('date', '>=', now()->subDays(30))->sum('purchase_price') >= $totalPurchases * 0.5
            ? 'increase'
            : 'decrease';

        // Total Payment Receipts Amount
        $totalPayments = paymentReceipt::sum('amount');
        $paymentsTrend = paymentReceipt::whereDate('date', '>=', now()->subDays(30))->sum('amount') >= $totalPayments * 0.5
            ? 'increase'
            : 'decrease';

        // Low Stock Products
        $lowStockProducts = product::whereColumn('stock_quantity', '<=', 'quantity_alert')->count();

        return [
            Stat::make('إجمالي المستخدمين', $totalUsers)
                ->description('عدد المستخدمين المسجلين')
                ->color('primary'),
            Stat::make('إجمالي المبيعات', number_format($totalSales, 2))
                ->description('إجمالي قيمة المبيعات')
                ->descriptionIcon($salesTrend === 'increase' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($salesTrend === 'increase' ? 'success' : 'danger'),
            Stat::make('إجمالي المشتريات', number_format($totalPurchases, 2))
                ->description('إجمالي قيمة المشتريات')
                ->descriptionIcon($purchasesTrend === 'increase' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($purchasesTrend === 'increase' ? 'success' : 'danger'),
            Stat::make('إجمالي الإيصالات', number_format($totalPayments, 2))
                ->description('إجمالي قيمة إيصالات الدفع')
                ->descriptionIcon($paymentsTrend === 'increase' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($paymentsTrend === 'increase' ? 'success' : 'danger'),
            Stat::make('منتجات منخفضة المخزون', $lowStockProducts)
                ->description('عدد المنتجات التي تحتاج إلى إعادة تعبئة')
                ->color($lowStockProducts > 0 ? 'warning' : 'success'),
        ];
    }
}



