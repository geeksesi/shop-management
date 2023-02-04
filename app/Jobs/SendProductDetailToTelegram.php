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

    private string $photo_url;
    private string $title;
    private string $description;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($photo_url, $title, $description)
    {
        $this->photo_url = $photo_url;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        TelegramService::send_photo($this->photo_url, env("TELEGRAM_RECEIVER_ID"), $this->title, $this->description);
    }
}
