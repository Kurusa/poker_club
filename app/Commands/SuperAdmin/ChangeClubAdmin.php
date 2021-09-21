<?php

namespace App\Commands\SuperAdmin;

use App\Commands\BaseCommand;
use App\Models\Club;
use App\Services\Status\UserStatusService;

class ChangeClubAdmin extends BaseCommand
{

    protected ?string $userStatus = UserStatusService::CHANGE_CLUB_ADMIN;

    function processCommand(array $params = [])
    {
        $clubId = $this->getCallbackDataByKey('clubId');
        $userId = $this->getCallbackDataByKey('userId');
        $value = $this->getCallbackDataByKey('v');

        $club = Club::find($clubId);
        if ($value) {
            try {
                $club->admins()->attach($userId);
            } catch (\Exception $exception) {
                return $this->getBot()->answerCallbackQuery(
                    $this->update->getCallbackQuery()->getId(),
                    $this->text['userIsAdminOfAnotherClub'],
                    true,
                );
            }
        } else {
            $club->admins()->detach($userId);
        }

        $this->triggerCommand(ListUsersToClub::class, [
            'clubId' => $clubId,
        ]);
    }

}
