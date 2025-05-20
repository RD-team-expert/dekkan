<?php

namespace App\Filament\Resources\PaymentReceiptsResource\Pages;

use App\Filament\Resources\PaymentReceiptsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentReceipts extends ListRecords
{
    protected static string $resource = PaymentReceiptsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
