@extends('layouts.app-layout')

@section('title', 'قائمة المدفوعات والإيصالات')
@section('header', 'قائمة المدفوعات والإيصالات')

@section('content')
    <div class="grid grid-cols-1 gap-4">
        @forelse ($paymentReceipts as $paymentReceipt)
            <div class="flex items-center bg-white p-2 rounded-md shadow-md">
                <div class="flex-1">
                    <h2 class="text-lg font-medium">{{ $paymentReceipt->type == 'payment' ? 'دفعة' : 'إيصال' }}</h2>
                    <p class="text-gray-600">التاريخ: {{ $paymentReceipt->date->format('Y-m-d H:i') }}</p>
                    <p class="text-gray-600">المبلغ: {{ number_format($paymentReceipt->amount, 2) }}</p>
                    <p class="text-gray-600">الملاحظات: {{ $paymentReceipt->notes ?? '-' }}</p>
                    <p class="text-gray-600">أدخلها: {{ $paymentReceipt->user->name }}</p>
                </div>
                <a href="{{ route('payment_receipts.show', $paymentReceipt) }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </a>
            </div>
        @empty
            <p class="text-center text-gray-500">لا توجد مدفوعات أو إيصالات حاليًا.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $paymentReceipts->links() }}
    </div>

    <a href="{{ route('payment_receipts.create') }}" class="fixed bottom-16 right-4 bg-blue-500 text-white rounded-full w-12 h-12 flex items-center justify-center hover:bg-blue-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
    </a>
@endsection