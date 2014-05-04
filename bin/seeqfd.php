<?php

use Ratchet\Server\IoServer;
use SEEQFD\Connection;

require dirname(__DIR__) . '/vendor/autoload.php';

$server = IoServer::factory(
    new Connection(),
    8080
);

$server->run();
