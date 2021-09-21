<?php

namespace App\Commands\Admin;

use App\Commands\{
    BaseCommand,
    MainMenu,
};
use App\Services\Status\{
    UserStatusService,
    ClubEventStatusService,
};

class Cancel extends BaseCommand
{

    function processCommand(array $params = [])
    {
        switch ($this->user->status) {
            case UserStatusService::WRITING_NEW_EVENT_DESCRIPTION:
                $this->user->club()->first()->events()->where('status', ClubEventStatusService::CREATING)->delete();
                $this->triggerCommand(MainMenu::class);
                break;
            case UserStatusService::EDIT_CLUB_TITLE:
            case UserStatusService::EDIT_CLUB_DESCRIPTION:
            case UserStatusService::EDIT_CLUB_TRAINING_GAMES:
                $this->triggerCommand(MainMenu::class);
                break;
        }
    }

}
