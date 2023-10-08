<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\OrderResource;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class OrderOverview extends BaseWidget
{
    protected function getStats(): array
    {

        return [
            Stat::make(
                'Total Orders',
                Order::get()->filter(fn($order) => $order->status() === 'placed_at')->count()
            ),
            Stat::make(
                'Packaged Orders',
                Order::get()->filter(fn($order) => $order->status() === 'packaged_at')->count()
            ),
            Stat::make(
                'Shipped Orders',
                Order::get()->filter(fn($order) => $order->status() === 'shipped_at')->count()
            ),
        ];
    }
}
