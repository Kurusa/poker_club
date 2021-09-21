<?php

namespace App\Commands\Admin\EditClub;

use App\Services\Status\UserStatusService;
use TelegramBot\Api\{Exception, InvalidArgumentException, Types\ReplyKeyboardMarkup};
use App\Commands\{
    BaseCommand,
    MainMenu,
};

class Controller extends BaseCommand
{

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        $action = $this->getCallbackDataByKey('a');
        if ($action) {
            $userStatus = match ($action) {
                'editClubTitle'         => UserStatusService::EDIT_CLUB_TITLE,
                'editClubDescription'   => UserStatusService::EDIT_CLUB_DESCRIPTION,
                'editClubTrainingGames' => UserStatusService::EDIT_CLUB_TRAINING_GAMES,
            };
            $this->user->update([
                'status' => $userStatus,
            ]);

            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $this->text['writeNewValue'],
                new ReplyKeyboardMarkup([[
                    $this->text['cancel']
                ]],false, true),
            );
        } else {
            $field = match ($this->user->status) {
                UserStatusService::EDIT_CLUB_TITLE          => 'title',
                UserStatusService::EDIT_CLUB_DESCRIPTION    => 'club_description',
                UserStatusService::EDIT_CLUB_TRAINING_GAMES => 'game_description',
            };

            $this->triggerCommand(MainMenu::class);
            $this->user->club()->first()->update([
                $field => $this->update->getMessage()->getText(),
            ]);

            $this->getBot()->sendMessage(
                $this->user->chat_id,
                $this->text['successfullyUpdated'],
            );
            $this->triggerCommand(ClubMenu::class);
        }
    }

}
