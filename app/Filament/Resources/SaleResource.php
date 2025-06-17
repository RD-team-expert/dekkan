<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

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
                Forms\Components\DateTimePicker::make('date_time')
                    ->required(),
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('total_products')
                    ->required()
                    ->numeric()
                    ->default(0),
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
                Tables\Columns\TextColumn::make('date_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_products')
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
                Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Start Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('End Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_time', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_time', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = Indicator::make('From ' . $data['from'])->removeField('from');
                        }

                        if ($data['until'] ?? null) {
                            $indicators[] = Indicator::make('Until ' . $data['until'])->removeField('until');
                        }

                        return $indicators;
                    }),
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
                        $query = Sale::query();

                        switch ($dateRange) {
                            case 'today':
                                $query->whereDate('date_time', Carbon::today());
                                break;
                            case 'week':
                                $query->whereBetween('date_time', [
                                    Carbon::now()->startOfWeek(),
                                    Carbon::now()->endOfWeek(),
                                ]);
                                break;
                            case 'month':
                                $query->whereBetween('date_time', [
                                    Carbon::now()->startOfMonth(),
                                    Carbon::now()->endOfMonth(),
                                ]);
                                break;
                        }

                        $records = $query->with(['user', 'product'])->get();

                        $csvData = "ID,User,Date,Product,Quantity,Total Products,Selling Price,Purchase Price,Profit,Created At,Updated At\n";
                        foreach ($records as $record) {
                            // Assuming Product or related Purchase model has selling_price and purchase_price
                            // Adjust these based on your actual model relationships
                            $sellingPrice = $record->product->selling_price ?? 0;
                            $purchasePrice = $record->product->purchase_price ?? 0;
                            $profit = $sellingPrice - $purchasePrice;

                            $csvData .= sprintf(
                                "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                                $record->id,
                                str_replace(',', '', $record->user?->name ?? 'N/A'),
                                $record->date_time,
                                str_replace(',', '', $record->product?->name ?? 'N/A'),
                                $record->quantity,
                                $record->total_products,
                                $sellingPrice,
                                $purchasePrice,
                                $profit,
                                $record->created_at,
                                $record->updated_at
                            );
                        }

                        return Response::streamDownload(function () use ($csvData) {
                            echo $csvData;
                        }, 'sales_' . $dateRange . '_' . now()->format('Ymd_His') . '.csv', [
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
