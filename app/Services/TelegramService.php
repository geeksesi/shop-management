<?php

namespace App\Services;

use Exception;

class TelegramService
{
    private string $url;

    public function __construct()
    {
        $this->url = 'https://api.telegram.org/bot' . env("TELEGRAM_BOT_TOKEN") . '/';
    }

    /**
     * @param string $_method
     * @param array $_parameters
     * @param string $_content_type
     * @return array|bool
     */
    public function execute(string $_method, array $_parameters, string $_content_type): array|bool
    {
        if (!isset($this->url)) {
            $this->__construct();
        }

        $url = $this->url . $_method;

        $curl = curl_init($url);
        if (empty($_parameters)) {
            printf("Parameters are empty");
            return false;
        }

        if ($_content_type == "application/json") {
            $_parameters = json_encode($_parameters);
        }

        curl_setopt($curl, CURLOPT_POSTFIELDS, $_parameters);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:' . $_content_type]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            printf(curl_error($curl));
            return false;
        }
        curl_close($curl);

        $result = json_decode($result, true);

        if (is_null($result)) {
            printf("output is empty!");
            return false;
        }
        return $result;
    }

    /**
     * @param mixed $_photo_path
     * @param string $_chat_id
     * @param string $_title
     * @param string $_description
     * @return array|bool
     */
    public function send_photo(mixed $_photo_path, string $_chat_id, string $_title, string $_description):array|bool
    {
        $parameters = [
            "photo" => $_photo_path,
            "chat_id" => $_chat_id,
            "caption" => sprintf("%s: \n %s", $_title, $_description)
        ];

        return $this->execute('sendPhoto', $parameters, "application/json");

    }

    /**
     * @param string $_photo_path
     * @param string $_chat_id
     * @param string $_title
     * @param string $_description
     * @return array|bool
     */
    public function send_photo_from_file(string $_photo_path, string $_chat_id,string $_title, string $_description):array|bool
    {
        $parameters = [
            "photo" => curl_file_create($_photo_path),
            "chat_id" => $_chat_id,
            "caption" => sprintf("%s: \n %s", $_title, $_description)
        ];

        return $this->execute('sendPhoto', $parameters, "multipart/form-data");

    }

}
