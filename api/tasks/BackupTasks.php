<?php

use Crunz\Schedule;

$schedule = new Schedule();

$env = getenv('APP_ENV') ?? 'dev';
$command = sprintf('%s bin/console britannia:backup --env=%s', PHP_BINARY, $env);

$task = $schedule->run($command);

$task
    ->cron('0 4,14 * * *')
    ->description('Backup.')
    ->preventOverlapping();

return $schedule;
