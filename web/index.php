<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript" src="assets/js/autobahn.min.js"></script>
    </head>
    <body>
        <script type="text/javascript">
            var connection = new autobahn.Connection({url: 'ws://app.local/ws', realm: 'realm1'});

            connection.onopen = function (session) {

                console.log('Connection opened, subscribing to "test" event.');

                // 1) subscribe to a topic
                function onEvent(args) {
                    console.log("Got data from subscribed event:");
                    console.log(args);
                }

                function onRpc(args) {
                    console.log('Got an RPC:');
                    console.log(args);
                }

                session.subscribe('test', onEvent);
                session.register('testrpc', onRpc);
            };

            connection.open();
        </script>
    </body>
</html>