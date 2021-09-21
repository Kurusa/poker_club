<?php

namespace App\Commands\ClubMenu;

use App\Services\{
    Status\UserStatusService,
    Utils\TelegramKeyboard,
};
use App\Commands\BaseCommand;
use App\Models\Club;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class ClubSchedule extends BaseCommand
{

    protected ?string $userStatus = UserStatusService::CLUB_DATES;

    function processCommand(array $params = [])
    {
        $clubId = $this->getCallbackDataByKey('clubId');

        $buttons = Club::find($clubId)->getDatesArray();
        TelegramKeyboard::$list = $buttons;
        TelegramKeyboard::$columns = 3;
        TelegramKeyboard::build();

        TelegramKeyboard::addButton($this->text['back'], [
            'a'      => 'backToClubMenu',
            'clubId' => $clubId,
        ]);

        $this->getBot()->editMessageText(
            $this->user->chat_id,
            $this->getMessageId(),
            $this->text['schedule'],
            'HTML',
            true,
            new InlineKeyboardMarkup(TelegramKeyboard::get()),
        );
    }

}
