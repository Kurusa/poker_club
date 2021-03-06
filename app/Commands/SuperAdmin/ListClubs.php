<?php

namespace App\Commands\SuperAdmin;

use TelegramBot\Api\{
    Exception,
    InvalidArgumentException,
    Types\Inline\InlineKeyboardMarkup,
};
use App\Services\{
    Status\UserStatusService,
    Utils\TelegramKeyboard,
};
use App\Commands\BaseCommand;
use App\Models\Club;

class ListClubs extends BaseCommand
{

    protected ?string $userStatus = UserStatusService::LIST_CLUBS;

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        TelegramKeyboard::$list    = Club::all()->pluck('title', 'id')->all();
        TelegramKeyboard::$columns = 2;
        TelegramKeyboard::$action  = 'listUsersToClub';
        TelegramKeyboard::build();

        if ($this->update->getCallbackQuery()) {
            $this->getBot()->editMessageText(
                $this->user->chat_id,
                $this->getMessageId(),
                $this->text['selectClub'],
                'HTML',
                true,
                new InlineKeyboardMarkup(TelegramKeyboard::get()),
            );
        } else {
            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $this->text['selectClub'],
                new InlineKeyboardMarkup(TelegramKeyboard::get()),
            );
        }
    }

}
