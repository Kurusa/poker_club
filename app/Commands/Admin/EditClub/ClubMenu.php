<?php

namespace App\Commands\Admin\EditClub;

use App\Services\{
    Status\UserStatusService,
    Utils\TelegramKeyboard,
};
use TelegramBot\Api\{
    Exception,
    InvalidArgumentException,
    Types\Inline\InlineKeyboardMarkup,
};
use App\Commands\BaseCommand;

class ClubMenu extends BaseCommand
{

    protected ?string $userStatus = UserStatusService::EDIT_CLUB_MENU;

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        $club = $this->user->club()->first();

        $text = 'Id: ' . '<b>'. $club->id .'</b>' . "\n";
        $text .= 'Название: ' . '<b>'. $club->title .'</b>' . "\n" . "\n";
        $text .= 'Описание: '. $club->club_description . "\n";
        $text .= 'Тренировочные игры: '. $club->game_description;

        $buttons[] = [
            'text' => $this->text['editClubTitle'],
            'callback' => [
                'a' => 'editClubTitle',
            ],
        ];
        $buttons[] = [
            'text' => $this->text['editClubDescription'],
            'callback' => [
                'a' => 'editClubDescription',
            ],
        ];
        TelegramKeyboard::$list = $buttons;
        TelegramKeyboard::$columns = 2;
        TelegramKeyboard::build();

        TelegramKeyboard::addButton($this->text['editClubTrainingGames'], [
            'a' => 'editClubTrainingGames',
        ]);

        if ($this->update->getCallbackQuery()) {
            $this->getBot()->editMessageText(
                $this->user->chat_id,
                $this->getMessageId(),
                $text,
                'HTML',
                true,
                new InlineKeyboardMarkup(TelegramKeyboard::get()),
            );
        } else {
            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $text,
                new InlineKeyboardMarkup(TelegramKeyboard::get()),
            );
        }
    }

}
