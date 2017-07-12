<?php

namespace mkiselev\broadcasting\broadcasters;

use mkiselev\broadcasting\helpers\StrHelper;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\redis\Connection;
use yii\web\ForbiddenHttpException;

class RedisBroadcaster extends Broadcaster
{
    /**
     * The Redis connection
     *
     * @var \yii\redis\Connection
     */
    public $redis = 'redis';

    public function init()
    {
        $this->redis = Instance::ensure($this->redis);
    }


    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param \yii\web\User $user
     * @param string $channelName
     * @return mixed
     * @throws \yii\web\ForbiddenHttpException
     */
    public function auth($user, $channelName)
    {
        if (StrHelper::startsWith($channelName, ['private-', 'presence-']) && $user->isGuest) {
            throw new ForbiddenHttpException();
        }

        $channelName = StrHelper::startsWith($channelName, 'private-')
            ? StrHelper::replaceFirst('private-', '', $channelName)
            : StrHelper::replaceFirst('presence-', '', $channelName);

        return parent::verifyUserCanAccessChannel($user, $channelName);
    }


    /**
     * Return the valid authentication response.
     *
     * @param \yii\web\User $user
     * @param mixed $result
     * @return mixed
     */
    public function validAuthenticationResponse($user, $result)
    {
        if (is_bool($result)) {
            return json_encode($result);
        }

        return json_encode(['channel_data' => [
            'user_id' => $user->id,
            'user_info' => $result,
        ]]);
    }


    /**
     * Broadcast the given event.
     *
     * @param array $channels
     * @param string $event
     * @param array $payload
     * @return void
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $payload = json_encode([
            'event' => $event,
            'data' => $payload,
            'socket' => ArrayHelper::remove($payload, 'socket'),
        ]);

        foreach ($this->formatChannels($channels) as $channel) {
            $this->redis->publish($channel, $payload);
        }
    }

}

