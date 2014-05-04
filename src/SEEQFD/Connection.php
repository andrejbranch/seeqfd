<?php

namespace SEEQFD;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Connection implements MessageComponentInterface
{
    protected $clients;
    protected $logger;
    protected $messenger;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->initializeLogger();
        $this->messenger = new Messenger();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        $this->logger->info(sprintf("New connection! %s", $conn->resourceId));
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $this->messenger->send($from, $msg);
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        $this->logger->info(sprintf("Connection %s has disconnected", $conn->resourceId));
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->logger->error(sprintf("An error has ocurred: %s", $e->getMessage()));

        $conn->close();
    }

    private function initializeLogger()
    {
        // create a log channel
        $this->logger = new Logger('SEEQFD');
        $this->logger->pushHandler(new StreamHandler(__DIR__.'/../../logs/dev.log', Logger::INFO));
        $this->logger->info('Logger initialized');
    }
}
