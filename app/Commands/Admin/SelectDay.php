<?php

namespace App\Commands\Admin;

use TelegramBot\Api\{
    Exception,
    InvalidArgumentException,
    Types\Inline\InlineKeyboardMarkup,
};
use App\Commands\BaseCommand;
use App\Services\Utils\TelegramKeyboard;
use Carbon\Carbon;

class SelectDay extends BaseCommand
{

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        $selectedMonth = $this->getCallbackDataByKey('v');

        $buttons = [];
        for ($i = 1; $i <= Carbon::parse($selectedMonth)->daysInMonth; $i++) {
            $text = $i;
            $callback = [
                'a' => 'day',
            ];

            # If event on this date exists - mark button with emoji
            $event = $this->user->club()->first()->events()->where('date', $i . '.' . Carbon::parse($selectedMonth)->format('m'))->first();
            if ($event) {
                $text .= ' 📄';
                $callback['eventId'] = $event->id;
            }

            $callback['date'] = $i . '.' . $selectedMonth;

            $buttons[] = [
                'text'     => $text,
                'callback' => $callback,
            ];
        }
        TelegramKeyboard::$list = $buttons;
        TelegramKeyboard::$columns = 5;
        TelegramKeyboard::build();

        TelegramKeyboard::addButton($this->text['back'], [
            'a' => 'backToMonthList',
        ]);

        if ($this->update->getCallbackQuery()) {
            $this->getBot()->editMessageText(
                $this->user->chat_id,
                $this->getMessageId(),
                $this->text['selectDay'],
                'HTML',
                true,
                new InlineKeyboardMarkup(TelegramKeyboard::get()),
            );
        } else {
            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $this->text['selectDay'],
                new InlineKeyboardMarkup(TelegramKeyboard::get()),
            );
        }
    }

}
