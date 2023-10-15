<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReducedStockResource\Pages;
use App\Filament\Resources\ReducedStockResource\RelationManagers;
use App\Models\ReducedStock;
use App\Models\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReducedStockResource extends Resource
{
    protected static ?string $model = Stock::class;
    protected static ?string $navigationGroup = 'Manage Stocks';
    protected static ?string $modelLabel = 'Reduced Stock';


    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_variation.product.title')
                    ->description(function(Stock $record) {
                        $str = "";
                        // $str .= "Product - {$record->product_variation->product->title} ";
                        $str .= $record->product_variation->parent ? "({$record->product_variation->parent->title})" : "";
                        $str .= " {$record->product_variation->type} - {$record->product_variation->title}";

                        return $str;
                    })
                    ->label('Product Variation')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->where('amount', '<', 0));
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
            'index' => Pages\ListReducedStocks::route('/'),
            // 'create' => Pages\CreateReducedStock::route('/create'),
            // 'edit' => Pages\EditReducedStock::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
