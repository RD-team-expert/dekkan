<x-filament-widgets::widget class="fi-stats-overview-widget">
    <x-filament::card>
        {{-- 1. عرض نموذج الفلاتر هنا --}}
        <div class="mb-4"> {{-- مسافة سفلية للفصل --}}
            {{ $this->form }}
        </div>

        {{-- 2. عرض الإحصائيات --}}
        @if ($stats = $this->getCachedStats())
            <div @class([
                'fi-wi-stats-overview-stats-container grid gap-6',
                'md:grid-cols-1' => $this->getColumns('md') === 1,
                'md:grid-cols-2' => $this->getColumns('md') === 2,
                'md:grid-cols-3' => $this->getColumns('md') === 3,
                'md:grid-cols-4' => $this->getColumns('md') === 4,
                'md:grid-cols-5' => $this->getColumns('md') === 5,
                'md:grid-cols-6' => $this->getColumns('md') === 6,
                'lg:grid-cols-1' => $this->getColumns('lg') === 1,
                'lg:grid-cols-2' => $this->getColumns('lg') === 2,
                'lg:grid-cols-3' => $this->getColumns('lg') === 3,
                'lg:grid-cols-4' => $this->getColumns('lg') === 4,
                'lg:grid-cols-5' => $this->getColumns('lg') === 5,
                'lg:grid-cols-6' => $this->getColumns('lg') === 6,
                'xl:grid-cols-1' => $this->getColumns('xl') === 1,
                'xl:grid-cols-2' => $this->getColumns('xl') === 2,
                'xl:grid-cols-3' => $this->getColumns('xl') === 3,
                'xl:grid-cols-4' => $this->getColumns('xl') === 4,
                'xl:grid-cols-5' => $this->getColumns('xl') === 5,
                'xl:grid-cols-6' => $this->getColumns('xl') === 6,
            ])>
                @foreach ($stats as $stat)
                    {{ $stat }}
                @endforeach
            </div>
        @endif
    </x-filament::card>
</x-filament-widgets::widget>