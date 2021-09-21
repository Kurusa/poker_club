<?php

namespace App\Commands\ClubMenu;

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

class ClubMenu extends BaseCommand
{

    protected ?string $userStatus = UserStatusService::CLUB_MENU;

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        if ($this->update->getMessage()) {
            $club = Club::firstWhere('title', $this->update->getMessage()->getText());
        } else {
            $club = Club::find($this->getCallbackDataByKey('clubId'));
        }

        TelegramKeyboard::$list = [
            [
                'text' => $this->text['schedule'],
                'callback' => [
                    'a'      => 'clubSchedule',
                    'clubId' => $club->id,
                ]
            ],
            [
                'text' => $this->text['trainingGames'],
                'callback' => [
                    'a'      => 'trainingGames',
                    'clubId' => $club->id,
                ]
            ]
        ];
        TelegramKeyboard::$columns = 2;
        TelegramKeyboard::build();

        if ($this->update->getCallbackQuery()) {
            $this->getBot()->editMessageText(
                $this->user->chat_id,
                $this->getMessageId(),
                $club->club_description,
                'HTML',
                true,
                new InlineKeyboardMarkup(TelegramKeyboard::get()),
            );
        } else {
            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $club->club_description,
                new InlineKeyboardMarkup(TelegramKeyboard::get()),
            );
        }
    }

}
