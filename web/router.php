<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Enqueue\AmqpExt\AmqpConnectionFactory;
use Thruway\Peer\Router;
use Enqueue\AmqpExt\AmqpContext;
use Thruway\Transport\RatchetTransportProvider;

$router = new Router;

class SubscribeModule implements \Thruway\Module\RealmModuleInterface
{

    /** @return array */
    public function getSubscribedRealmEvents()
    {
        return [
            "SubscribeMessageEvent" => ["handleSubscribeMessage", 100],
        ];
    }

    public function handleSubscribeMessage(\Thruway\Event\MessageEvent $event)
    {
        /** @var \Thruway\Message\SubscribeMessage $msg */
        $msg = $event->message;

        $options = [
            'host' => '127.0.0.1',
            'port' => 5672,
            'vhost' => '/',
            'login' => 'guest',
            'password' => 'guest',
            'persisted' => false,
        ];

        $context = (new AmqpConnectionFactory($options))->createContext();
        $queue = $context->createQueue('test_queue');
        $context->declareQueue($queue);
        $message = $context->createMessage('WHOOOOO');
        $context->createProducer()->send($queue, $message);
        // fire and forget FTW
    }
}

// can set to 0000
$transportProvider = new RatchetTransportProvider("127.0.0.1", 9090);

$router->getRealmManager()->getRealm('realm1')->addModule(new SubscribeModule(/** @todo pass in loop **/));

$router->addTransportProvider($transportProvider);

$router->start();