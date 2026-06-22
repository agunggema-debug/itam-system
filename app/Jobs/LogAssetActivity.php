<?php

namespace App\Jobs;

use App\Models\Asset;
use App\Models\AssetLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class LogAssetActivity implements ShouldQueue
{
    use Dispatchable, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Asset $asset,
        public ?int $userId,
        public string $action,
        public ?string $description = null,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        AssetLog::create([
            'asset_id' => $this->asset->id,
            'user_id' => $this->userId,
            'action' => $this->action,
            'description' => $this->description,
        ]);
    }
}
