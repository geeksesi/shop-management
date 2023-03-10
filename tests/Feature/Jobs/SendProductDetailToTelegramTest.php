<?php

namespace Jobs;

use App\Jobs\SendProductDetailToTelegram;
use App\Models\Product;
use App\Services\TelegramService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SendProductDetailToTelegramTest extends TestCase
{
    use WithFaker;
    public function testSendPhotoToTelegram(){

        $telegramServiceMock = \Mockery::mock(TelegramService::class);
        $product = Product::factory()->forCategory()->make();
        $photo_file = $product->thumbnail;
        $title = $product->name;
        $description = $product->description;
        $photo_url = Storage::fake('products_thumbnail')->putFileAs('', $photo_file, 'test.jpg');


        $telegramServiceMock->shouldReceive('send_photo_from_file')
            ->once()
            ->with($photo_url, $title, $description);

        $sendProductDetailToTelegram = new SendProductDetailToTelegram($photo_url, $title, $description);
        $sendProductDetailToTelegram->handle($telegramServiceMock);

    }
}
