<?php

namespace App\Commands\Admin;

use App\Commands\Admin\CreateEvent\CreateEvent;
use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class CoordinateDate extends BaseCommand
{

    function processCommand(array $params = [])
    {
        switch ($this->user->status) {
            case UserStatusService::SHOW_USERS_TO_EVENT_LIST:
                $this->triggerCommand(ShowUsersToEventList::class);
                break;
            case UserStatusService::CREATE_EVENT:
                $this->getBot()->deleteMessage($this->user->chat_id, $this->getMessageId());
                $this->triggerCommand(CreateEvent::class);
                break;
        }
    }

}
