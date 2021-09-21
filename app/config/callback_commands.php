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
    ClubMenu,
    TrainingGames,
};
use App\Commands\ClubEvent;
use App\Commands\ClubSchedule;
use App\Commands\UserEventDecisionMaker;

return [
    'clubSchedule'       => ClubSchedule::class,
    'clubEventByDate'    => ClubEvent::class,
    'userEventDecision'  => UserEventDecisionMaker::class,
    'trainingGames'      => TrainingGames::class,
    'listUsersToClub'    => ListUsersToClub::class,
    'backToClubList'     => ListClubs::class,
    'changeClubAdmin'    => ChangeClubAdmin::class,
    'month'              => SelectDay::class,
    'backToMonthList'    => SelectMonth::class,
    'day'                => CoordinateDate::class,
    'edit'               => Edit::class,
    'delete'             => Delete::class,
    'backToClubMenu'     => ClubMenu::class,
    'backToClubSchedule' => ClubSchedule::class,
];
