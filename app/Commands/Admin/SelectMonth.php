<?php

namespace App\Commands\Admin;

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
use Carbon\Carbon;

class SelectMonth extends BaseCommand
{

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        if ($this->update->getMessage()) {
            if ($this->update->getMessage()->getText() == $this->text['whoWillComeToEvent']) {
                $status = UserStatusService::SHOW_USERS_TO_EVENT_LIST;
            } elseif ($this->update->getMessage()->getText() == $this->text['createEvent']) {
                $status = UserStatusService::CREATE_EVENT;
            }

            if (isset($status)) {
                $this->user->update([
                    'status' => $status,
                ]);
            }
        }

        $months = collect(
            Carbon::today()->startOfMonth()
                ->subMonths(12)
                ->monthsUntil(Carbon::today()->startOfMonth())
        )->mapWithKeys(fn ($month) => [
            $month->month => $month->monthName
        ])->all();

        $buttons = [];
        foreach ($months as $month) {
            $buttons[] = [
                'text'     => $month,
                'callback' => [
                    'a'  => 'month',
                    'v'  => $month,
                ],
            ];
        }
        TelegramKeyboard::$list = $buttons;
        TelegramKeyboard::$columns = 2;
        TelegramKeyboard::build();

        if ($this->update->getCallbackQuery()) {
            $this->getBot()->editMessageText(
                $this->user->chat_id,
                $this->getMessageId(),
                $this->text['selectMonth'],
                'HTML',
                true,
                new InlineKeyboardMarkup(TelegramKeyboard::get()),
            );
        } else {
            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $this->text['selectMonth'],
                new InlineKeyboardMarkup(TelegramKeyboard::get()),
            );
        }
    }

}
