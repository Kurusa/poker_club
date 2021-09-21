<?php

namespace App\Commands;

use TelegramBot\Api\{
    Exception,
    InvalidArgumentException,
    Types\ReplyKeyboardMarkup,
};
use App\Commands\ClubMenu\ClubMenu;
use App\Models\Club;
use App\Services\Status\UserStatusService;

class MainMenu extends BaseCommand
{

    protected ?string $userStatus = UserStatusService::MAIN_MENU;

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        $clubTitles = Club::all()->pluck('title')->all();

        # If club is selected - show club menu
        if ($this->update->getMessage()) {
            if (in_array($this->update->getMessage()->getText(), $clubTitles)) {
                $this->triggerCommand(ClubMenu::class);
                return;
            }
        }

        $buttons = [];
        $buttons[] = $clubTitles;

        # Superadmin buttons
        if ($this->user->is_super_admin) {
            $buttons[] = [$this->text['setClubAdmins']];
        }

        # Admin buttons
        if ($this->user->club()->first()) {
            $buttons[] = [
                $this->text['whoWillComeToEvent'],
                $this->text['createEvent'],
            ];
        }

        $this->getBot()->sendMessageWithKeyboard(
            $this->user->chat_id,
            $this->text['mainMenu'],
            new ReplyKeyboardMarkup($buttons,false, true),
        );
    }

}
