<?php

namespace App\Filament\Resources\PurchasesResource\Pages;

use App\Filament\Resources\PurchasesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchases extends EditRecord
{
    protected static string $resource = PurchasesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
