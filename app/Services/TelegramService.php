<?php

namespace App\Services;

use Exception;

class TelegramService
{
    private static string $url;

    /**
     * @return void
     */
    private static function init(): void
    {
        self::$url = 'https://api.telegram.org/bot' . env("TELEGRAM_BOT_TOKEN") . '/';
    }

    /**
     * @param string $_method
     * @param array $_parameters
     * @return array|bool
     * @throws Exception
     */
    private static function execute(string $_method, array $_parameters, string $_content_type): array|bool
    {
        if (!isset(self::$url)) {
            self::init();
        }

        $url = self::$url . $_method;

        $curl = curl_init($url);
        if (empty($_parameters)) {
            throw new Exception("No parameters provide");
        }

        if ($_content_type == "application/json") {
            $_parameters = json_encode($_parameters);
        }

        curl_setopt($curl, CURLOPT_POSTFIELDS, $_parameters);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:' . $_content_type]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new Exception(curl_error($curl));
        }
        curl_close($curl);

        $result = json_decode($result, true);

        if (is_null($result)) {
            throw new Exception("output is empty!");
        }
        return $result;
    }

    /**
     * @param string $_text
     * @param string $_chat_id
     * @return array|bool
     * @throws Exception
     */
    public static function send_message(string $_text, string $_chat_id): array|bool
    {
        $parameters = [
            "text" => $_text,
            "chat_id" => $_chat_id
        ];
        return self::execute('sendMessage', $parameters, "application/json");
    }

    /**
     * @param mixed $_photo
     * @param string $_chat_id
     * @param string $_caption
     * @return array|bool
     * @throws Exception
     */
    public static function send_photo(mixed $_photo, string $_chat_id, string $_caption):array|bool
    {
        $parameters = [
            "photo" => $_photo,
            "chat_id" => $_chat_id,
            "caption" => $_caption
        ];

        return self::execute('sendPhoto', $parameters, "application/json");

    }

    /**
     * @param string $_photo_path
     * @param string $_chat_id
     * @param string $_caption
     * @return array|bool
     * @throws Exception
     */
    public static function send_photo_from_file(string $_photo_path, string $_chat_id, string $_caption):array|bool
    {
        $parameters = [
            "photo" => curl_file_create($_photo_path),
            "chat_id" => $_chat_id,
            "caption" => $_caption
        ];

        return self::execute('sendPhoto', $parameters, "multipart/form-data");

    }

}
