<?php

use App\Commands\Admin\{
    Cancel,
    SelectMonth,
};
use App\Commands\SuperAdmin\ListClubs;
use App\Commands\Admin\EditClub\EditClubMenu;

return [
    'setClubAdmins'      => ListClubs::class,
    'whoWillComeToEvent' => SelectMonth::class,
    'createEvent'        => SelectMonth::class,
    'cancel'             => Cancel::class,
    'editClub'           => EditClubMenu::class,
];
