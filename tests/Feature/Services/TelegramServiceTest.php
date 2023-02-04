<?php

namespace Services;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\TelegramService;
use Mockery\MockInterface;

class TelegramServiceTest extends TestCase
{
    use WithFaker;
    public function testSendPhotoSuccessful(){
        $photo_path = "https://mcdn.wallpapersafari.com/medium/38/96/EjQb2Y.jpg";
        $chat_id = env('TELEGRAM_RECEIVER_ID');
        $title = $this->faker->text;
        $description = $this->faker->text;

        $parameters = [
            "photo" => $photo_path,
            "chat_id" => $chat_id,
            "caption" => sprintf("%s: \n %s", $title, $description)
        ];

        $telegramServiceMock = $this->partialMock(TelegramService::class);
        $telegramServiceMock->shouldReceive('execute')->with('sendPhoto', $parameters, "application/json")->andReturn(True);


        $result = $telegramServiceMock->send_photo($photo_path, $chat_id, $title, $description);
        $this->assertTrue($result);
    }

    public function testSendPhotoFail(){
        $photo_path = $this->faker->text;
        $chat_id = env('TELEGRAM_RECEIVER_ID');
        $title = $this->faker->text;
        $description = $this->faker->text;

        $parameters = [
            "photo" => $photo_path,
            "chat_id" => $chat_id,
            "caption" => sprintf("%s: \n %s", $title, $description)
        ];

        $telegramServiceMock = $this->partialMock(TelegramService::class);
        $telegramServiceMock->shouldReceive('execute')
                            ->with('sendPhoto', $parameters, "application/json")
                            ->andReturn(false);

        $result = $telegramServiceMock->send_photo($photo_path, $chat_id, $title, $description);
        $this->assertFalse($result);
    }
}
