<?php

namespace Services;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use \App\Models\Product;
use Tests\TestCase;
use App\Services\TelegramService;


class TelegramServiceTest extends TestCase
{
    use WithFaker;
    public function testSendPhotoSuccessful(){
        $product = Product::factory()->forCategory()->make();
        $chat_id = env('TELEGRAM_RECEIVER_ID');
        $photo_file = $product->thumbnail;
        $title = $product->name;
        $description = $product->description;


        $url = sprintf('https://api.telegram.org/bot%s/%s', env("TELEGRAM_BOT_TOKEN"), 'sendPhoto');
        Http::fake([
            $url => Http::response(['response'], 200, ['header']),
        ]);

        $telegramService = new TelegramService();
        $result = $telegramService->send_photo_from_file($photo_file, $chat_id, $title, $description);
        $this->assertEquals(200, $result['status_code']);
    }

    public function testSendPhotoFail(){
        $product = Product::factory()->forCategory()->make();
        $chat_id = env('TELEGRAM_RECEIVER_ID');
        $photo_file = $product->thumbnail;
        $title = $product->name;
        $description = $product->description;

        $url = sprintf('https://api.telegram.org/bot%s/%s', env("TELEGRAM_BOT_TOKEN"), 'sendPhoto');
        Http::fake([
            $url => Http::response(['response'], 400, ['header']),
        ]);

        $telegramService = new TelegramService();
        $result = $telegramService->send_photo_from_file($photo_file, $chat_id, $title, $description);
        $this->assertEquals(400, $result['status_code']);
    }

}
