<?php

use Crunz\Schedule;

$schedule = new Schedule();

$env = getenv('APP_ENV') ?? 'dev';
$command = sprintf('%s bin/console britannia:messages:send --env=%s', PHP_BINARY, $env);

$task = $schedule->run($command);
$task
    ->minute([0, 20, 40])
    ->hour(['10-22'])
    ->description('Send sms and emails.')
    ->preventOverlapping();

return $schedule;
