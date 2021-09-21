<?php

namespace App\Commands\Admin;

use App\Services\{
    Status\ClubEventStatusService,
    Status\UserStatusService,
};
use TelegramBot\Api\{
    Exception,
    InvalidArgumentException,
    Types\Inline\InlineKeyboardMarkup,
    Types\ReplyKeyboardMarkup,
};
use App\Commands\BaseCommand;
use App\Models\ClubEvent;

class Edit extends BaseCommand
{

    protected ?string $userStatus = UserStatusService::WRITING_NEW_EVENT_DESCRIPTION;

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        $eventId = $this->getCallbackDataByKey('eventId');
        ClubEvent::find($eventId)->update([
            'status' => ClubEventStatusService::EDITING,
        ]);

        $this->getBot()->editMessageReplyMarkup(
            $this->user->chat_id,
            $this->getMessageId(),
            new InlineKeyboardMarkup([]),
        );
        $this->getBot()->sendMessageWithKeyboard(
            $this->user->chat_id,
            $this->text['writeDescription'],
            new ReplyKeyboardMarkup([[
                $this->text['cancel']
            ]], false, true),
        );
    }

}
