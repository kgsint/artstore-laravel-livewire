<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductVariation;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductVariationResource\Pages;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\ProductVariationResource\RelationManagers;

class ProductVariationResource extends Resource
{
    protected static ?string $model = ProductVariation::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Manage Products';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                        ->schema([
                            Grid::make()->schema([
                                Forms\Components\Select::make('product_id')
                                                        ->relationship('product', 'title')
                                                        ->required(),
                                Forms\Components\TextInput::make('title')
                                    ->label('Title for variation (black, white, 0.5, etc...)')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('price')
                                                                ->label('Price (optional)')
                                                                ->numeric()
                                                                ->default(0)
                                                                ->prefix('$'),
                                Forms\Components\TextInput::make('type')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('parent_id')
                                    ->label('Parent Product Variation (Optional)')
                                    ->relationship('parent', 'title')
                                    ->getOptionLabelFromRecordUsing(
                                        fn(Model $record) =>"{$record->title} ({$record->product?->title} {$record?->parent?->title})"
                                        ),
                                Forms\Components\TextInput::make('order')
                                    ->numeric(),
                                Forms\Components\TextInput::make('sku')
                                    ->label('SKU')
                                    ->maxLength(255),
                                ]),


            ])->columnSpan(8),
            Section::make()
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('image'),
                            ])->columnSpan(4),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.title')
                    ->description(function(ProductVariation $variation) {
                        return "{$variation->type} - {$variation->title}";
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('usd', 100)
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent.type')
                    ->label('Belongs To')
                    ->description(function(ProductVariation $variation) {
                        return $variation->parent ? "child of {$variation->parent->product->title} - {$variation->parent->title}" : "";
                    })
                    ->placeholder('N/A')
                    ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('order')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('product.title');
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
            'index' => Pages\ListProductVariations::route('/'),
            'create' => Pages\CreateProductVariation::route('/create'),
            'view' => Pages\ViewProductVariation::route('/{record}'),
            'edit' => Pages\EditProductVariation::route('/{record}/edit'),
        ];
    }
}
