<?php

use App\Commands\Admin\{
    Cancel,
    SelectMonth,
};
use App\Commands\SuperAdmin\ListClubs;
use App\Commands\Admin\EditClub\ClubMenu;
use App\Commands\Admin\Mailing\{
    Text,
    Image,
};

return [
    'setClubAdmins'      => ListClubs::class,
    'whoWillComeToEvent' => SelectMonth::class,
    'createEvent'        => SelectMonth::class,
    'cancel'             => Cancel::class,
    'editClub'           => ClubMenu::class,
    'createMailing'      => Text::class,
    'createMailingForMyClub' => Text::class,
];
