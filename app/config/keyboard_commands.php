<?php

use App\Commands\Admin\{
    CreateEvent\Cancel,
    SelectMonth,
};
use App\Commands\SuperAdmin\ListClubs;

return [
    'setClubAdmins'      => ListClubs::class,
    'whoWillComeToEvent' => SelectMonth::class,
    'createEvent'        => SelectMonth::class,
    'cancel'             => Cancel::class,
];
