<?php

namespace SEEQFD;

use Ratchet\ConnectionInterface;

class Messenger
{
    public function send(ConnectionInterface $from, $message)
    {
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:5555");

        $socket->send(json_encode(array('test')));
    }
}
