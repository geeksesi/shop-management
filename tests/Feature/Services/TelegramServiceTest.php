<?php

namespace Services;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Services\TelegramService;


class TelegramServiceTest extends TestCase
{
    use WithFaker;
    public function testSendPhotoSuccessful(){
        $photo_path = "https://mcdn.wallpapersafari.com/medium/38/96/EjQb2Y.jpg";
        $chat_id = env('TELEGRAM_RECEIVER_ID');
        $title = $this->faker->text;
        $description = $this->faker->text;

        Http::fake([
            'https://api.telegram.org/*' => Http::response(['response'], 200, ['header']),
        ]);

        $telegramService = new TelegramService();
        $result = $telegramService->send_photo($photo_path, $chat_id, $title, $description);
        $this->assertEquals(200, $result['status_code']);
    }

    public function testSendPhotoFail(){
        $photo_path = $this->faker->text;
        $chat_id = env('TELEGRAM_RECEIVER_ID');
        $title = $this->faker->text;
        $description = $this->faker->text;

        Http::fake([
            'https://api.telegram.org/*' => Http::response(['response'], 400, ['header']),
        ]);

        $telegramService = new TelegramService();
        $result = $telegramService->send_photo($photo_path, $chat_id, $title, $description);
        $this->assertEquals(400, $result['status_code']);
    }
}
