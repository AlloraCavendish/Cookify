<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\User;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Recipes', Recipe::count())
                ->icon('heroicon-o-book-open')
                ->color('success'),
            Stat::make('Total Ingredients', Ingredient::count())
                ->icon('heroicon-o-beaker')
                ->color('warning'),
            Stat::make('Total Users', User::count())
                ->icon('heroicon-o-users')
                ->color('info'),
        ];
    }
}