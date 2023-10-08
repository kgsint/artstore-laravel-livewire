<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Manage Orders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->hiddenOn('edit'),
                Forms\Components\Select::make('shipping_address_id')
                    ->relationship('shippingAddress', 'id')
                    ->hiddenOn('edit'),
                Forms\Components\Select::make('shipping_type_id')
                    ->relationship('shippingType', 'title')
                    ->hiddenOn('edit'),
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->hiddenOn('edit'),
                Forms\Components\TextInput::make('email')
                    ->hiddenOn('edit')
                    ->email(),
                Forms\Components\TextInput::make('subtotal')
                    ->hiddenOn('edit'),
                Forms\Components\DateTimePicker::make('placed_at'),
                Forms\Components\DateTimePicker::make('packaged_at'),
                Forms\Components\DateTimePicker::make('shipped_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->placeholder('guest')
                    ->sortable(),
                Tables\Columns\TextColumn::make('shippingType.title')
                    ->label('Delivery Service')
                    ->sortable(),
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->money('usd', 100),
                Tables\Columns\TextColumn::make('current_status')
                                                    ->badge()
                                                    ->color(fn(string $state) => match($state) {
                                                        'Order Placed' => 'gray',
                                                        'Order Packaged' => 'warning',
                                                        'Order Shipped' => 'success',
                                                    }),
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
            'index' => Pages\ListOrders::route('/'),
            // 'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
