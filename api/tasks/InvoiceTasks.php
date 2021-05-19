<?php

use Crunz\Schedule;

$schedule = new Schedule();

$env = getenv('APP_ENV') ?? 'dev';
$command = sprintf('%s bin/console britannia:invoices:update --env=%s', PHP_BINARY, $env);

$task = $schedule->run($command);

$task
    ->cron('30 0 1 * *')
    ->description('Generación de recibos.')
    ->preventOverlapping();

return $schedule;
