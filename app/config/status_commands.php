<?php

use App\Commands\Admin\CreateEvent\Description;
use App\Commands\Admin\EditClub\EditController;
use App\Services\Status\UserStatusService;

return [
    UserStatusService::WRITING_NEW_EVENT_DESCRIPTION => Description::class,
    UserStatusService::EDIT_CLUB_TITLE               => EditController::class,
    UserStatusService::EDIT_CLUB_DESCRIPTION         => EditController::class,
    UserStatusService::EDIT_CLUB_TRAINING_GAMES      => EditController::class,
];
