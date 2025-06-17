<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentReceiptResource\Pages;
use App\Models\PaymentReceipt;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;

class PaymentReceiptResource extends Resource
{
    protected static ?string $model = PaymentReceipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Forms\Components\DateTimePicker::make('date')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        'payment' => 'Payment',
                        'receipt' => 'Receipt',
                    ])
                    ->default('payment'),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_csv')
                    ->label('Export CSV')
                    ->form([
                        Forms\Components\Select::make('date_range')
                            ->label('Date Range')
                            ->options([
                                'today' => 'Today',
                                'week' => 'This Week',
                                'month' => 'This Month',
                            ])
                            ->required()
                            ->default('today'),
                    ])
                    ->action(function (array $data) {
                        $dateRange = $data['date_range'];
                        $query = PaymentReceipt::query();

                        switch ($dateRange) {
                            case 'today':
                                $query->whereDate('date', Carbon::today());
                                break;
                            case 'week':
                                $query->whereBetween('date', [
                                    Carbon::now()->startOfWeek(),
                                    Carbon::now()->endOfWeek(),
                                ]);
                                break;
                            case 'month':
                                $query->whereBetween('date', [
                                    Carbon::now()->startOfMonth(),
                                    Carbon::now()->endOfMonth(),
                                ]);
                                break;
                        }

                        $records = $query->with('user')->get();

                        $csvData = "ID,User,Date,Type,Amount,Notes,Created At,Updated At\n";
                        foreach ($records as $record) {
                            $csvData .= sprintf(
                                "%s,%s,%s,%s,%s,%s,%s,%s\n",
                                $record->id,
                                str_replace(',', '', $record->user?->name ?? 'N/A'),
                                $record->date,
                                $record->type,
                                $record->amount,
                                str_replace(',', '', $record->notes ?? ''),
                                $record->created_at,
                                $record->updated_at
                            );
                        }

                        return Response::streamDownload(function () use ($csvData) {
                            echo $csvData;
                        }, 'payment_receipts_' . $dateRange . '_' . now()->format('Ymd_His') . '.csv', [
                            'Content-Type' => 'text/csv',
                        ]);
                    })
                    ->icon('heroicon-o-document'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentReceipts::route('/'),
            'create' => Pages\CreatePaymentReceipt::route('/create'),
            'edit' => Pages\EditPaymentReceipt::route('/{record}/edit'),
        ];
    }
}
