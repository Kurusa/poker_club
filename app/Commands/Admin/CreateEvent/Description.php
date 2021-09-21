<?php

namespace App\Commands\Admin\CreateEvent;

use TelegramBot\Api\{
    Exception,
    InvalidArgumentException,
};
use App\Commands\{
    BaseCommand,
    MainMenu,
};
use App\Services\Status\ClubEventStatusService;

class Description extends BaseCommand
{

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        $event = $this->user->club()->first()->events()->whereIn('status', [ClubEventStatusService::CREATING, ClubEventStatusService::EDITING])->first();
        $event->update([
            'status'      => '',
            'description' => $this->update->getMessage()->getText(),
        ]);

        $this->getBot()->sendMessage(
            $this->user->chat_id,
            $this->text['successfullyCreated'] . ' на дату ' . $event->date . '.',
        );
        $this->triggerCommand(MainMenu::class);
    }

}
