<?php

namespace App\Commands\ClubMenu;

use App\Models\{
    ClubEvent,
    UserEventDecision,
};
use TelegramBot\Api\{
    Exception,
    InvalidArgumentException,
};
use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class UserEventDecisionMaker extends BaseCommand
{

    protected ?string $userStatus = UserStatusService::USER_EVENT_DECISION;

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        $eventId = $this->getCallbackDataByKey('eventId');
        $clubId  = $this->getCallbackDataByKey('clubId');
        $value = $this->getCallbackDataByKey('v');

        UserEventDecision::updateOrCreate(
            [
                'user_id'   => $this->user->id,
                'event_id'  => $eventId,
                'club_id'   => $clubId,
            ],
            [
                'user_id'   => $this->user->id,
                'event_id'  => $eventId,
                'club_id'   => $clubId,
                'value'     => intval($value),
            ]
        );

        if (intval($value)) {
            $notifyText = $this->text['youAreSignedUp'];
        } else {
            $notifyText = $this->text['youAreSkipped'];
        }

        $this->getBot()->sendMessage($this->user->chat_id, $notifyText);

        $this->triggerCommand(\App\Commands\ClubMenu\ClubEvent::class, [
            'eventId' => $eventId,
        ]);
    }

}
