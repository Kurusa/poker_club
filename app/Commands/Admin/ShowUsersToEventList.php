<?php

namespace App\Commands\Admin;

use TelegramBot\Api\{
    Exception,
    InvalidArgumentException,
};
use App\Commands\BaseCommand;
use App\Models\ClubEvent;
use Carbon\Carbon;

class ShowUsersToEventList extends BaseCommand
{

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        $eventId = $this->getCallbackDataByKey('eventId');
        $date = Carbon::parse($this->getCallbackDataByKey('date'))->format('d.m');

        $event = ClubEvent::find($eventId);
        if ($event) {
            $text = "<b>" . $date . "</b>" . "\n" . "\n";
            foreach ($event->decisions as $key => $decision) {
                $aTag = "<a href='tg://user?id=";
                $aTag .= $decision->user->chat_id;
                $aTag .= "'>";

                $text .= $key + 1 . '. ' . $aTag . $decision->user->name . "</a> ";

                if ($decision->value) {
                    $text .= '✅';
                } else {
                    $text .= '❌';
                }

                $text .= "\n";
            }

            $this->getBot()->sendMessage(
                $this->user->chat_id,
                $text,
                'HTML',
            );
        } else {
            $this->getBot()->sendMessage(
                $this->user->chat_id,
                $this->text['eventNotFount'],
            );
        }
    }

}
