<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\TelegramService;

class SendProductDetailToTelegram implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private string $photo_url, private string $title, private string $description)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TelegramService $telegram_service)
    {
        $telegram_service->send_photo_from_file($this->photo_url, $this->title, $this->description);
    }
}
