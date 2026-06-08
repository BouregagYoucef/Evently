<?php

namespace App\Filament\Host\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HostStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        
        $totalEvents = \App\Models\Event::where('host_id', $user->id)->count();
        $totalGuests = \App\Models\Guest::whereHas('event', function ($q) use ($user) {
            $q->where('host_id', $user->id);
        })->count();
        $confirmedGuests = \App\Models\Invitation::whereHas('event', function ($q) use ($user) {
            $q->where('host_id', $user->id);
        })->where('status', 'CONFIRMED')->count();

        return [
            Stat::make('إجمالي المناسبات (Total Events)', $totalEvents)
                ->description('المناسبات التي قمت بإنشائها')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),
                
            Stat::make('إجمالي الضيوف (Total Guests)', $totalGuests)
                ->description('ضيوفك في جميع المناسبات')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('الحضور المؤكد (Confirmed RSVPs)', $confirmedGuests)
                ->description('الضيوف الذين أكدوا حضورهم')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info'),
        ];
    }
}
