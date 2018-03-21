Yii2-broadcasting
=================
Websocket broadcasting module

[![Latest Stable Version](https://poser.pugx.org/mkiselev/yii2-broadcasting/v/stable?format=flat-square)](https://packagist.org/packages/mkiselev/yii2-broadcasting)
[![Total Downloads](https://poser.pugx.org/mkiselev/yii2-broadcasting/downloads?format=flat-square)](https://packagist.org/packages/mkiselev/yii2-broadcasting)
[![Latest Unstable Version](https://poser.pugx.org/mkiselev/yii2-broadcasting/v/unstable?format=flat-square)](https://packagist.org/packages/mkiselev/yii2-broadcasting)
[![License](https://poser.pugx.org/mkiselev/yii2-broadcasting/license?format=flat-square)](https://packagist.org/packages/mkiselev/yii2-broadcasting)
[![Monthly Downloads](https://poser.pugx.org/mkiselev/yii2-broadcasting/d/monthly?format=flat-square)](https://packagist.org/packages/mkiselev/yii2-broadcasting)

This module is made under inspiration of laravel echo and compatible with libraries.

There are several broadcast tools available for your choice:

1) [NullBroadcaster](broadcasters/NullBroadcaster.php) Doing nothing, just a stub
2) [LogBroadcaster](broadcasters/LogBroadcaster.php) Broadcast events to application log
3) [RedisBroadcaster](broadcasters/RedisBroadcaster.php) Broadcast by Redis using Pub/Sub feature (required [yii2-redis](https://github.com/yiisoft/yii2-redis))
4) RatchetBroadcaster (coming soon...)
5) PusherBroadcaster Broadcast by using pusher.com (coming soon...)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist mkiselev/yii2-broadcasting "*"
```

or add

```
"mkiselev/yii2-broadcasting": "*"
```

to the require section of your `composer.json` file.


Application configuration
-------------------------
Configure module for use some broadcaster and configure channels auth callbacks:
```php
'bootstrap' => ['broadcasting'],
'modules' => [
    'broadcasting' => [
        'class' => \mkiselev\broadcasting\Module::class,
        'broadcaster' => [
            'class' => \mkiselev\broadcasting\broadcasters\RedisBroadcaster::class,
            // By default will be used redis application component, but you can configure as you want
            'redis' => [
                'class' => \yii\redis\Connection::class,
            ],
            // Configure auth callback for private and presitance chanells
            'channels' => [
                'signal' => function (\yii\web\User $user) {
                    return $user->can('something');
                },
            ],
        ],
    ],
],
```


Socket.io server configuration
------------------------------
This module is compilable with [laravel-echo-server](https://github.com/tlaverdure/laravel-echo-server)

Please follow to laravel-echo-server instructions to install and run them.


Usage
-----

### Server side
Write your event extended by \mkiselev\broadcasting\events\BroadcastEvent like this:
```php
<?php

namespace common\models;

use mkiselev\broadcasting\channels\PrivateChannel;
use mkiselev\broadcasting\events\BroadcastEvent;

class SignalEvent extends BroadcastEvent
{
    public $someParam = 42;

    public function broadcastOn()
    {
        return new PrivateChannel('signal');
    }

    public function broadcastAs()
    {
        return 'new';
    }
}
```

And broadcast it somewhere:
```php
(new common\models\SignalEvent(['someParam' => 146]))->toOthers()->broadcast();
```


### Client side
Register mkiselev\broadcasting\assets\EchoAsset
import socket.io library https://github.com/tlaverdure/laravel-echo-server#socketio-client-library

```js
window.io = io;
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});

Echo.channel('private-signal')
    .listen('.new', function(e) {
        console.log(e.someParam);
    });
```

#### [CHANGELOG](CHANGELOG.md)
