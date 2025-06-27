<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPayrollChunk implements ShouldQueue
{
    use Queueable, Batchable;

    protected Collection $chunk;

    /**
     * Create a new job instance.
     */
    public function __construct(Collection $chunk)
    {
        $this->chunk = $chunk;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->chunk as $user) {
            logger('Doing somethign to for ' . $user->name);
        }
    }
}
