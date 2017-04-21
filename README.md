Usage
-

- Run `vagrant up`
- Type in your admin password when prompted so 
  [hostmanager plugin](https://github.com/devopsgroup-io/vagrant-hostmanager) can update your /etc/hosts file with
  "app.local"
- Go and make yourself a cup of tea
- The following should all be run within vagrant (`vagrant ssh` then `cd /vagrant` obviously)
- Run the broker (router): `php web/router.php` in 1 terminal window
- Open `http://app.local` on your host machine and open console
- Run a worker: `php web/worker.php` in another terminal window
- Observe javascript console and see the worker having picked up the job and sent it over RPC to be sent back over WS

Everything is being offloaded into a queue and utilize a 5 second wait in the worker to show how this isn't blocking.