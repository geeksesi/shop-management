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
    public function execute(string $method, array $params):array
    {
        $url = sprintf('https://api.telegram.org/bot%s/%s', $this->token, $method);

        $response = Http::asMultipart()->connectTimeout(30)->post($url, $params);

        return ['result'=>$response->json('result', []), 'status_code'=>$response->status()];
    }

    /**
     * @param mixed $photo_path
     * @param string $chat_id
     * @param string $title
     * @param string $description
     * @return array|bool
     */
    public function send_photo_from_file($photo_url, string $chat_id, string $title, string $description):array|bool
    {

        $params = [
            "photo" => Storage::disk('local')->readStream($photo_url),
            "chat_id" => $chat_id,
            "caption" => sprintf("%s: \n %s", $title, $description)
        ];

        return $this->execute('sendPhoto', $params);
    }

}

