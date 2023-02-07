<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class TelegramService
{
    private string $token;


    public function __construct()
    {
        $this->token = env("TELEGRAM_BOT_TOKEN");
    }

    /**
     * @param string $method
     * @param array $params
     * @param string $content_type
     * @return array
     */
    public function execute(string $method, array $params, string $content_type):array
    {
        $url = sprintf('https://api.telegram.org/bot%s/%s', $this->token, $method);
        $response = "";
        if($content_type == 'multipart/form-data'){
            $response = Http::asMultipart()->connectTimeout(30)->post($url, $params);
        }
        elseif ($content_type == 'application/json'){
            $response = Http::connectTimeout(30)->post($url, $params);
        }

        return ['result'=>$response->json('result', []), 'status_code'=>$response->status()];
    }

    /**
     * @param mixed $photo_path
     * @param string $chat_id
     * @param string $title
     * @param string $description
     * @return array|bool
     */
    public function send_photo_from_file($photo_file, string $chat_id, string $title, string $description):array|bool
    {

        $path = Storage::putFile('products_thumbnail', $photo_file);

        $params = [
            "photo" => Storage::disk('local')->readStream($path),
            "chat_id" => $chat_id,
            "caption" => sprintf("%s: \n %s", $title, $description)
        ];

        return $this->execute('sendPhoto', $params, "multipart/form-data");
    }

}

