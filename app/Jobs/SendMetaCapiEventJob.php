<?php

namespace App\Jobs;

use App\Services\Analytics\MetaCapiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMetaCapiEventJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $eventName,
        public ?string $eventId = null,
        public array $userData = [],
        public array $customData = [],
        public ?string $sourceUrl = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(MetaCapiService $capiService): void
    {
        $capiService->sendEvent(
            $this->eventName,
            $this->eventId,
            $this->userData,
            $this->customData,
            $this->sourceUrl
        );
    }
}
