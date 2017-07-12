<?php

namespace mkiselev\broadcasting\broadcasters;

interface BroadcasterInterface
{
    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param \yii\web\User $user
     * @param string $channelName
     * @return mixed
     */
    public function auth($user, $channelName);


    /**
     * Return the valid authentication response.
     *
     * @param \yii\web\User $user
     * @param  mixed $result
     * @return mixed
     */
    public function validAuthenticationResponse($user, $result);


    /**
     * Broadcast the given event.
     *
     * @param array $channels
     * @param string $event
     * @param array $payload
     * @return void
     */
    public function broadcast(array $channels, $event, array $payload = []);

}
