<?php

use Crunz\Schedule;

$schedule = new Schedule();

$env = getenv('APP_ENV') ?? 'dev';
$command = sprintf('%s bin/console britannia:maintenance --env=%s', PHP_BINARY, $env);

$task = $schedule->run($command);

$task
    ->cron('30 3 * * *')
    ->description('Operaciones de mantenimiento.')
    ->preventOverlapping();

return $schedule;
