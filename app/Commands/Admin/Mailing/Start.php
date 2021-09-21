<?php

namespace App\Commands\Admin\Mailing;

use App\Commands\ClubMenu\ClubEvent;
use App\Models\UserEventDecision;
use TelegramBot\Api\{
    Exception,
    InvalidArgumentException,
};
use App\Commands\MainMenu;
use App\Models\User;
use App\Services\Status\MailingStatusService;
use App\Commands\BaseCommand;

class Start extends BaseCommand
{

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    function processCommand(array $params = [])
    {
        $mailing = $this->user->mailings()->where('status', MailingStatusService::CREATING)->first();
        $users = User::all();

        foreach ($users as $user) {
            if ($mailing->club_id) {
                if (
                    !\App\Models\ClubEvent::where('club_id', $mailing->club_id)->where('user_id', $user->id)->first() &&
                    !\App\Models\ClubAdmin::where('club_id', $mailing->club_id)->where('user_id', $user->id)->first() &&
                    !\App\Models\UserEventDecision::where('club_id', $mailing->club_id)->where('user_id', $user->id)->first()
                ) {
                    continue;
                }
            }

            if ($mailing->image) {
                $this->getBot()->sendPhoto(
                    $user->chat_id,
                    $mailing->image,
                    $mailing->text ?: ''
                );
            } else {
                $this->getBot()->sendMessage($user->chat_id, $mailing->text);
            }
        }

        $mailing->update([
            'status' => MailingStatusService::DONE,
        ]);
        $this->triggerCommand(MainMenu::class, [
            'skipClubName' => true,
        ]);
    }

}
