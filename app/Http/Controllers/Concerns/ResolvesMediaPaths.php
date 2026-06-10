<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Event;

trait ResolvesMediaPaths
{
    /**
     * Resolve all storage paths in event's content_data to full public URLs.
     * This converts relative paths (e.g. "events/gallery/img.jpg") to
     * full URLs (e.g. "https://domain.com/storage/events/gallery/img.jpg")
     * without touching the database record.
     */
    protected function resolveEventMedia(Event $event): Event
    {
        $resolved = $this->resolvePaths($event->content_data ?? []);
        $event->content_data = $resolved;
        return $event;
    }

    private function resolvePaths(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map(fn($v) => $this->resolvePaths($v), $value);
        }

        if (is_string($value) && !empty($value)) {
            // Only resolve if it looks like a storage path (starts with events/ or has an image extension)
            $isStoragePath = str_starts_with($value, 'events/') || preg_match('/\.(jpg|jpeg|png|webp|gif|svg)$/i', $value);

            if ($isStoragePath && !str_starts_with($value, 'http') && !str_starts_with($value, 'data:') && !str_starts_with($value, '/')) {
                return asset('storage/' . $value);
            }
        }

        return $value;
    }
}
