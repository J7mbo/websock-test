<?php

use Thruway\Connection;

require_once __DIR__ . '/../vendor/autoload.php';

$connection = new Connection(
    [
        "realm"   => 'realm1',
        "url"     => 'ws://127.0.0.1:9090',
    ]
);

$connection->on('open', function (\Thruway\ClientSession $session) use ($connection) {

    //send an RPC
//    $session->call('testrpc', ['Hello, world from PHP!!!', "Second"], [], ["acknowledge" => true])->then(
//        function () use ($connection) {
//            $connection->close(); //You must close the connection or this will hang
//            echo "RPC Acknowledged!\n";
//        },
//        function ($error) {
//            // publish failed
//            echo "Publish Error {$error}\n";
//        }
//    );
//
//    // publish to subscribed users
//    $session->publish('test', ['Publishing data', 'no 2'], [], ["acknowledge" => true])->then(
//        function () use ($connection) {
//            $connection->close(); //You must close the connection or this will hang
//            echo "Publish Acknowledged!\n";
//        },
//        function ($error) {
//            // publish failed
//            echo "Publish Error {$error}\n";
//        }
//    );


});

$connection->open();