<?php

namespace App\Commands\Admin\Mailing;

use App\Commands\ClubMenu\ClubMenu;
use App\Services\Status\MailingStatusService;
use TelegramBot\Api\{
    Exception,
    InvalidArgumentException,
    Types\ReplyKeyboardMarkup,
};
use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;

class Club extends BaseCommand
{

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        if ($this->user->status == UserStatusService::MAILING_CLUB) {
            $club = \App\Models\Club::where('title', $this->update->getMessage()->getText())->first();
            if ($club) {
                $this->user->mailings()->where('status', MailingStatusService::CREATING)->update([
                    'club_id' => $club->id,
                ]);
            }

            $this->triggerCommand(Start::class);
        } else {
            $this->user->update([
                'status' => UserStatusService::MAILING_CLUB,
            ]);

            $clubTitles = \App\Models\Club::all()->pluck('title')->all();

            $this->getBot()->sendMessageWithKeyboard(
                $this->user->chat_id,
                $this->text['selectClubForMailing'],
                new ReplyKeyboardMarkup([
                    $clubTitles,
                    [
                        $this->text['cancel'],
                        $this->text['skip'],
                    ]
                ], false, true),
            );
        }
    }

}
