<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessCsvImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $eventId;
    public $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct(int $eventId, string $filePath)
    {
        $this->eventId = $eventId;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $event = Event::find($this->eventId);
        if (!$event) {
            return;
        }

        $fullPath = Storage::disk('local')->path($this->filePath);
        if (!file_exists($fullPath)) {
            return;
        }

        $file = fopen($fullPath, 'r');
        $header = fgetcsv($file); // Read the header row

        // Expected headers: name, email, phone, type, companions_count
        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);

            // Skip if no name
            if (empty($data['name'])) {
                continue;
            }

            // Check event quota
            if ($event->guests()->count() >= $event->max_guests) {
                break; // Stop importing if we hit the limit
            }

            $guest = Guest::create([
                'event_id' => $event->id,
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'type' => strtoupper($data['type'] ?? 'REGULAR'),
                'companions_count' => intval($data['companions_count'] ?? 0),
            ]);
        }

        fclose($file);

        // Delete the temporary file
        Storage::disk('local')->delete($this->filePath);
    }
}
