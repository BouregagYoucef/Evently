<?php

namespace App\Services;

use App\Models\Invitation;

class AnalyticsService
{
    /**
     * Get aggregated statistics for an event's invitations.
     */
    public function getEventStats(int $eventId): array
    {
        $stats = Invitation::selectRaw("status, count(*) as count")
            ->where('event_id', $eventId)
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
        $total = array_sum($stats);
        
        return [
            'total' => $total,
            'stats' => $stats,
        ];
    }
}
