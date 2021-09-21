<?php

namespace App\Commands\Admin\CreateEvent;

use App\Services\{
    Status\ClubEventStatusService,
    Status\UserStatusService,
    Utils\TelegramKeyboard,
};
use TelegramBot\Api\{
    Exception,
    InvalidArgumentException,
    Types\Inline\InlineKeyboardMarkup,
    Types\ReplyKeyboardMarkup,
};
use App\Commands\BaseCommand;
use App\Models\ClubEvent;
use Carbon\Carbon;

class CreateEvent extends BaseCommand
{

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        $eventId = $this->getCallbackDataByKey('eventId');
        $date = Carbon::parse($this->getCallbackDataByKey('date'))->format('d.m');

        $buttons = [];
        if ($eventId) {
            $event = ClubEvent::find($eventId);

            $buttons[] = [
                'text' => $this->text['delete'],
                'callback' => [
                    'a'       => 'delete',
                    'eventId' => $eventId,
                ],
            ];
            $buttons[] = [
                'text' => $this->text['edit'],
                'callback' => [
                    'a'       => 'edit',
                    'eventId' => $eventId,
                ],
            ];
            TelegramKeyboard::$list = $buttons;
            TelegramKeyboard::$columns = 2;
            TelegramKeyboard::build();

            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                'Событие на день <b>' . $date . '</b> уже существует.' . "\n" . "\n" . '"' . $event->description . '"' . "\n" . "\n" . 'Вы можете удалить или отредактировать его.',
                new InlineKeyboardMarkup(TelegramKeyboard::get()),
            );
        } else {
            $this->user->update([
                'status' => UserStatusService::WRITING_NEW_EVENT_DESCRIPTION,
            ]);

            $event = new ClubEvent;
            $event->user_id = $this->user->id;
            $event->club_id = $this->user->club()->first()->id;
            $event->status  = ClubEventStatusService::CREATING;
            $event->date    = $date;
            $event->save();

            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $this->text['writeDescription'],
                new ReplyKeyboardMarkup([[
                    $this->text['cancel']
                ]],false, true),
            );
        }
    }

}
