<?php

namespace App\Services\Utils;

use TelegramBot\Api\{
    BotApi,
    Exception,
    InvalidArgumentException,
    Types\Message,
};

class Api extends BotApi
{

    public function __construct($token, $trackerToken = null)
    {
        parent::__construct($token, $trackerToken);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function sendMessageWithKeyboard(
        int $chatId,
        string $text,
        $keyboard
    ): Message {
        return $this->sendMessage(
            $chatId,
            $text,
            'HTML',
            true,
            null,
            $keyboard,
        );
    }

}
