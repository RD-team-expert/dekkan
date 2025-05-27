<?php

namespace App\Filament\Resources\OverviewResource\Widgets;

use App\Models\PaymentReceipt;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section; // لإضافة حاوية للفلتر إذا أردت
use Filament\Forms\Contracts\HasForms; // *مهم*
use Filament\Forms\Concerns\InteractsWithForms; // *مهم*

class StatsOverview extends BaseWidget implements HasForms // *مهم: إضافة HasForms*
{
    use InteractsWithForms; // *مهم: إضافة Trait*

    public ?string $filter = '30';
    public ?string $startDate = null;
    public ?string $endDate = null;

    // 1. تحديد ملف عرض مخصص للويدجت
    protected static string $view = 'filament.widgets.custom-stats-overview-with-filters';

    // 2. (اختياري) إزالة أو إفراغ getHeaderActions إذا لم تعد تستخدمها
    // protected function getHeaderActions(): array
    // {
    //     return [];
    // }

    // 3. دالة mount لتهيئة النموذج (إذا لزم الأمر، خاصة إذا كانت القيم الافتراضية معقدة)
    public function mount(): void
    {
        $this->form->fill([
            'filter' => $this->filter,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }

    // 4. تعريف مخطط نموذج الفلاتر
    protected function getFormSchema(): array
    {
        return [
            // يمكنك وضع الفلاتر داخل Section لترتيب أفضل في الواجهة
            Section::make(__('فلترة الإحصائيات')) // "Filter Statistics"
                ->columns(3) // أو العدد المناسب من الأعمدة
                ->schema([
                    Select::make('filter')
                        ->label(__('الفترة الزمنية')) // "Time Period"
                        ->options([
                            '7' => __('آخر 7 أيام'), // "Last 7 days"
                            '30' => __('آخر 30 يومًا'), // "Last 30 days"
                            '90' => __('آخر 90 يومًا'), // "Last 90 days"
                            'custom' => __('فترة مخصصة'), // "Custom Period"
                        ])
                        ->default('30')
                        ->reactive()
                        ->afterStateUpdated(function ($state) {
                            $this->filter = $state;
                            if ($state !== 'custom') {
                                $this->startDate = null;
                                $this->endDate = null;
                                // تحديث النموذج أيضًا إذا كنت تريد أن يعكس DatePickers ذلك فورًا
                                $this->form->fill([
                                    'startDate' => null,
                                    'endDate' => null,
                                ]);
                            }
                            // لا حاجة لاستدعاء updateStats() هنا عادةً، Livewire سيتكفل بالتحديث
                        }),
                    DatePicker::make('startDate')
                        ->label(__('تاريخ البداية')) // "Start Date"
                        ->visible(fn ($get) => $get('filter') === 'custom') // استخدم $get('filter') للوصول لقيمة الفلتر في النموذج
                        ->reactive()
                        ->afterStateUpdated(function ($state) {
                            $this->startDate = $state;
                        }),
                    DatePicker::make('endDate')
                        ->label(__('تاريخ النهاية')) // "End Date"
                        ->visible(fn ($get) => $get('filter') === 'custom')
                        ->reactive()
                        ->afterStateUpdated(function ($state) {
                            $this->endDate = $state;
                        }),
                ])
        ];
    }

    // دالة getStats ودالة getDateRange تبقى كما هي، حيث تعتمد على الخصائص العامة
    // $this->filter, $this->startDate, $this->endDate

    protected function getStats(): array
    {
        [$startDate, $endDate] = $this->getDateRange();

        $totalUsers = User::count();
        $totalSales = Sale::whereBetween('date_time', [$startDate, $endDate])->sum('total_price');
        $totalPurchases = Purchase::whereBetween('date', [$startDate, $endDate])->sum('purchase_price');
        $totalPayments = PaymentReceipt::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'quantity_alert')->count();

        return [
            Stat::make(__('إجمالي المستخدمين'), $totalUsers)
                ->description(__('عدد المستخدمين المسجلين'))
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make(__('إجمالي المبيعات'), number_format($totalSales, 2) . ' ' . __())
                ->description(__('إجمالي قيمة المبيعات'))
                ->icon('heroicon-o-shopping-bag')
                ->color('success'),
            Stat::make(__('إجمالي المشتريات'), number_format($totalPurchases, 2) . ' ' . __(''))
                ->description(__('إجمالي قيمة المشتريات'))
                ->icon('heroicon-o-shopping-cart')
                ->color('warning'),
            Stat::make(__('إجمالي الإيصالات'), number_format($totalPayments, 2) . ' ' . __(''))
                ->description(__('إجمالي قيمة الإيصالات'))
                ->color('info'),
            Stat::make(__('منتجات منخفضة المخزون'), $lowStockProducts)
                ->description(__('تحتاج إلى إعادة تعبئة'))
                ->icon('heroicon-o-exclamation-triangle')
                ->color($lowStockProducts > 0 ? 'danger' : 'success'),
        ];
    }

    protected function getDateRange(): array
    {
        // تأكد من أن $this->startDate و $this->endDate يتم تعيينهما بشكل صحيح من النموذج
        // إذا كان النموذج هو المصدر الوحيد لهذه القيم بعد الآن.
        $filterValue = $this->form->getState()['filter'] ?? $this->filter; // احصل على القيمة من النموذج أو الخاصية العامة
        $startDateValue = $this->form->getState()['startDate'] ?? $this->startDate;
        $endDateValue = $this->form->getState()['endDate'] ?? $this->endDate;


        if ($filterValue === 'custom' && $startDateValue && $endDateValue) {
            return [
                \Carbon\Carbon::parse($startDateValue)->startOfDay(),
                \Carbon\Carbon::parse($endDateValue)->endOfDay()
            ];
        }

        return [
            now()->subDays((int)$filterValue)->startOfDay(),
            now()->endOfDay()
        ];
    }

    // لم تعد بحاجة لـ updateStats() إذا كانت Livewire تقوم بالتحديث تلقائيًا
    // protected function updateStats(): void
    // {
    //     // $this->emitSelf('updateStats'); // أو طريقة أخرى لتحديث الويدجت إذا لزم الأمر
    // }
}