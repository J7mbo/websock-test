<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Enqueue\AmqpExt\AmqpConnectionFactory;
use Enqueue\AmqpExt\AmqpContext;
use Thruway\Connection;

$options = [
    'host' => '127.0.0.1',
    'port' => 5672,
    'vhost' => '/',
    'login' => 'guest',
    'password' => 'guest',
    'persisted' => false,
];
$context = (new AmqpConnectionFactory($options))->createContext();

/** @var AmqpContext $context */
$queue = $context->createQueue('test_queue');
$context->declareQueue($queue);
$consumer = $context->createConsumer($queue);

while (true) {
    $message = $consumer->receive($timeout = 10);

    if (!$message)
    {
        continue;
    }

    echo "GOT MESSAGE: " . $message->getBody();

    $imagePath = $message->getBody();

    $consumer->acknowledge($message);

    // Send back with extra data
    $sendBack = $message->getBody() . ' ALTERED BY WORKER';

    // simulate long process
    sleep(5);

    $connection = new Connection(
        [
            "realm"   => 'realm1',
            "url"     => 'ws://127.0.0.1:9090',
        ]
    );

    $connection->on('open', function (\Thruway\ClientSession $session) use ($connection, $sendBack) {

        $session->call('testrpc', [$sendBack], [], ["acknowledge" => true])->then(
            function () use ($connection) {
                $connection->close(); //You must close the connection or this will hang
                echo "RPC Acknowledged!\n";
            },
            function ($error) {
                // publish failed
                echo "Publish Error {$error}\n";
            }
        );
    });

    $connection->open();
}