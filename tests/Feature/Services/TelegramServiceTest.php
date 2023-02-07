<?php

namespace Services;

use App\Models\Product;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;



class TelegramServiceTest extends TestCase
{
    public function testSendPhotoSuccessful(){
        $product = Product::factory()->forCategory()->make();
        $chat_id = env('TELEGRAM_RECEIVER_ID');
        $photo_url = $product->thumbnail;
        $title = $product->name;
        $description = $product->description;

        $photo_url = Storage::disk('local')->putFileAs('testing', $photo_url, 'test.jpg');


        $telegramServiceMock = $this->partialMock(TelegramService::class);
        $telegramServiceMock->shouldReceive('execute')
            ->once()->andReturnUsing(function (string $method, array $params){
                if(gettype($params['photo']) == 'resource'){
                    return ['status_code' => 200];
                }else{
                    return ['status_code' => 400];
                }
        });

        $result = $telegramServiceMock->send_photo_from_file($photo_url, $chat_id, $title, $description);
        $this->assertEquals(200, $result['status_code']);
    }

}
