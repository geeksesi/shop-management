<?php

namespace Jobs;

use App\Jobs\SendProductDetailToTelegram;
use App\Services\TelegramService;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SendProductDetailToTelegramTest extends TestCase
{
    use WithFaker;
    public function testSendPhotoToTelegram(){

        $telegramServiceMock = $this->mock(TelegramService::class);

        $photo_url = "https://mcdn.wallpapersafari.com/medium/38/96/EjQb2Y.jpg";
        $chat_id = env('TELEGRAM_RECEIVER_ID');
        $title = $this->faker->text;
        $description = $this->faker->text;

        $telegramServiceMock->shouldReceive('send_photo_from_file')
            ->once()
            ->with($photo_url, $chat_id , $title, $description);

        $sendProductDetailToTelegram = new SendProductDetailToTelegram($photo_url, $title, $description);
        $sendProductDetailToTelegram->handle($telegramServiceMock);

    }
}
