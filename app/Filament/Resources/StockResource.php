<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockResource\Pages;
use App\Models\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $navigationGroup = 'Manage Stocks';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_variation_id')
                    ->label('Product Variation')
                    ->relationship('product_variation', 'title' , modifyQueryUsing: function(Builder $query) {
                        return $query->whereNotNull('sku');
                    })
                    ->getOptionLabelFromRecordUsing(
                        function(Model $record) {
                            // if(!$record->parent) return;
                            return "{$record->product->title} ({$record?->parent?->title} {$record->title})";
                        }
                        )
                    // ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->where('amount', '>', 0));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageStocks::route('/'),
        ];
    }

    // only product variation with sku
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('product_variation', function($variation) {
            return $variation->whereNotNull('sku');
        });
    }
}
