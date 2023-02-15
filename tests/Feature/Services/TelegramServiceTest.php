<?php

namespace Services;

use App\Models\Product;
use App\Services\TelegramService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;



class TelegramServiceTest extends TestCase
{
    use WithFaker;
    public function testSendPhotoSuccessful(){
        $product = Product::factory()->forCategory()->make();
        $photo_file = $product->thumbnail;
        $title = $product->name;
        $description = $product->description;
        $chat_id = $this->faker->text;

        $photo_url = Storage::fake('products_thumbnail')->putFileAs('', $photo_file, 'test.jpg');



        $telegramServiceMock = \Mockery::mock(TelegramService::class, [$chat_id])->makePartial();

        $telegramServiceMock->shouldReceive('execute')
            ->once()->andReturnUsing(function (string $method, array $params){
                if(gettype($params['photo']) == 'resource'){
                    return ['status_code' => 200];
                }else{
                    return ['status_code' => 400];
                }
        });

        $result = $telegramServiceMock->send_photo_from_file($photo_url, $title, $description);
        $this->assertEquals(200, $result['status_code']);
    }

}
