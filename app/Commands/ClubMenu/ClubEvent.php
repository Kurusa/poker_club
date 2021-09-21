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

class ClubEvent extends BaseCommand
{

    protected ?string $userStatus = UserStatusService::CLUB_EVENT;

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        $callbackData = $this->getCallbackData();
        $clubId = $callbackData['clubId'];
        $date   = $callbackData['date'];

        if (!$clubId || !$date) {
            $clubEvent = \App\Models\ClubEvent::find($params['eventId']);
            $date = $clubEvent->date;
            $clubId = $clubEvent->club_id;
        } else {
            $clubEvent = \App\Models\ClubEvent::where('club_id', $clubId)->where('date', $date)->first();
        }

        if ($clubEvent) {
            $userEventDecision = $clubEvent->decisions()->where('user_id', $this->user->id)->first();

            if (!$userEventDecision) {
                TelegramKeyboard::$list = [
                    [
                        'text' => $this->text['signUpForEvent'],
                        'callback' => [
                            'a'       => 'userEventDecision',
                            'eventId' => $clubEvent->id,
                            'v'       => true,
                        ]
                    ],
                    [
                        'text' => $this->text['skipEvent'],
                        'callback' => [
                            'a'       => 'userEventDecision',
                            'eventId' => $clubEvent->id,
                            'v'       => true,
                        ]
                    ]
                ];
            } else {
                $value = !$userEventDecision->value;
                $text = $userEventDecision->value ? $this->text['skipEventAnyway'] : $this->text['signUpForEventAnyway'];

                TelegramKeyboard::$list = [
                    [
                        'text' => $text,
                        'callback' => [
                            'a'       => 'userEventDecision',
                            'eventId' => $clubEvent->id,
                            'v'       => $value,
                        ]
                    ],
                ];
            }

            TelegramKeyboard::$columns = 2;
            TelegramKeyboard::build();
            TelegramKeyboard::addButton($this->text['back'], [
                'a'      => 'backToClubSchedule',
                'clubId' => $clubId,
            ]);

            $this->getBot()->editMessageText(
                $this->user->chat_id,
                $this->getMessageId(),
                $date . "\n" . $clubEvent->description,
                'HTML',
                true,
                new InlineKeyboardMarkup(TelegramKeyboard::get()),
            );
        } else {
            $this->getBot()->sendMessage(
                $this->user->chat_id,
                $this->text['eventNotFount'],
            );
        }
    }

}
