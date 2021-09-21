<?php

use App\Commands\SuperAdmin\{
    ChangeClubAdmin,
    ListClubs,
    ListUsersToClub,
};
use App\Commands\Admin\{
    CoordinateDate,
    Delete,
    Edit,
    SelectDay,
    SelectMonth,
};
use App\Commands\ClubMenu\{
    ClubEvent,
    ClubMenu,
    ClubSchedule,
    TrainingGames,
    UserEventDecisionMaker,
};
use App\Commands\Admin\EditClub\Controller;

return [
    'clubSchedule'          => ClubSchedule::class,
    'backToClubSchedule'    => ClubSchedule::class,
    'clubEventByDate'       => ClubEvent::class,
    'userEventDecision'     => UserEventDecisionMaker::class,
    'trainingGames'         => TrainingGames::class,
    'listUsersToClub'       => ListUsersToClub::class,
    'backToClubList'        => ListClubs::class,
    'changeClubAdmin'       => ChangeClubAdmin::class,
    'month'                 => SelectDay::class,
    'backToMonthList'       => SelectMonth::class,
    'day'                   => CoordinateDate::class,
    'edit'                  => Edit::class,
    'delete'                => Delete::class,
    'backToClubMenu'        => ClubMenu::class,
    'editClubTitle'         => Controller::class,
    'editClubDescription'   => Controller::class,
    'editClubTrainingGames' => Controller::class,
];
