<?php

namespace App\Filament\Resources\PaymentReceiptsResource\Pages;

use App\Filament\Resources\PaymentReceiptsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentReceipts extends CreateRecord
{
    protected static string $resource = PaymentReceiptsResource::class;
}
