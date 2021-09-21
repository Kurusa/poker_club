<?php

namespace App\Commands\ClubMenu;

use App\Services\{
    Status\UserStatusService,
    Utils\TelegramKeyboard,
};
use App\Commands\BaseCommand;
use App\Models\Club;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class TrainingGames extends BaseCommand
{

    protected ?string $userStatus = UserStatusService::TRAINING_GAMES;

    function processCommand(array $params = [])
    {
        $clubId = $this->getCallbackDataByKey('clubId');
        $club = Club::find($clubId);

        TelegramKeyboard::addButton($this->text['back'], [
            'a'      => 'backToClubMenu',
            'clubId' => $clubId,
        ]);

        $this->getBot()->editMessageText(
            $this->user->chat_id,
            $this->getMessageId(),
            $club->game_description ?:$this->text['descriptionIsEmpty'],
            'HTML',
            true,
            new InlineKeyboardMarkup(TelegramKeyboard::get()),
        );
    }

}
