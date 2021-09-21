<?php

namespace App\Commands\Admin;

use App\Commands\BaseCommand;
use App\Models\ClubEvent;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class Delete extends BaseCommand
{

    function processCommand(array $params = [])
    {
        $eventId = $this->getCallbackDataByKey('eventId');
        ClubEvent::find($eventId)->delete();

        $this->getBot()->editMessageText(
            $this->user->chat_id,
            $this->getMessageId(),
            $this->text['successfullyDeleted'],
            'HTML',
            true,
            new InlineKeyboardMarkup([]),
        );
    }

}
