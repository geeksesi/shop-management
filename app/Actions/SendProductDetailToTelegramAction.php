<?php

namespace App\Actions;

use App\Jobs\SendProductDetailToTelegram;
use App\Services\TelegramService;

class SendProductDetailToTelegramAction
{
    public function handle(mixed $data){
        SendProductDetailToTelegram::dispatchIf(isset($data["social_message"]), $data["thumbnail"], $data["name"]
                                                , $data["social_message"]);

    }
}
