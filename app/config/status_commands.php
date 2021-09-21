<?php

use App\Commands\Admin\Mailing\{
    Text,
    Image,
    Club,
};
use App\Commands\Admin\CreateEvent\Description;
use App\Commands\Admin\EditClub\Controller;
use App\Services\Status\UserStatusService;

return [
    UserStatusService::WRITING_NEW_EVENT_DESCRIPTION => Description::class,
    UserStatusService::EDIT_CLUB_TITLE               => Controller::class,
    UserStatusService::EDIT_CLUB_DESCRIPTION         => Controller::class,
    UserStatusService::EDIT_CLUB_TRAINING_GAMES      => Controller::class,
    UserStatusService::MAILING_TEXT                  => Text::class,
    UserStatusService::MAILING_IMAGE                 => Image::class,
    UserStatusService::MAILING_CLUB                  => Club::class,
];
