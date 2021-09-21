<?php

namespace App\Commands\SuperAdmin;

use App\Models\{
    Club,
    User
};
use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;
use App\Services\Utils\TelegramKeyboard;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class ListUsersToClub extends BaseCommand
{

    protected ?string $userStatus = UserStatusService::LIST_USERS_TO_CLUB;

    function processCommand(array $params = [])
    {
        $clubId = $this->getCallbackDataByKey('id') ?: $params['clubId'];

        $club = Club::find($clubId);
        $users = User::all();

        $buttons = [];
        foreach ($users as $user) {
            $text = $user->name;

            // set
            $value = true;
            if ($user->club()->first()->id == $clubId) {
                $text .= '✅';
                // unset
                $value = false;
            }

            $buttons[] = [
                'text' => $text,
                'callback' => [
                    'a'      => 'changeClubAdmin',
                    'userId' => $user->id,
                    'clubId' => $club->id,
                    'v'      => $value,
                ],
            ];
        }

        TelegramKeyboard::$list = $buttons;
        TelegramKeyboard::$columns = 2;
        TelegramKeyboard::build();

        TelegramKeyboard::addButton($this->text['back'], [
            'a' => 'backToClubList',
        ]);

        $this->getBot()->editMessageText(
            $this->user->chat_id,
            $this->getMessageId(),
            $this->text['selectClubAdmins'] . ' "' . $club->title . '"',
            'HTML',
            true,
            new InlineKeyboardMarkup(TelegramKeyboard::get())
        );
    }

}
