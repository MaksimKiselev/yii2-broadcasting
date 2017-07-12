<?php

namespace mkiselev\broadcasting\broadcasters;

class NullBroadcaster extends Broadcaster
{
    /**
     * {@inheritdoc}
     */
    public function auth($user, $channelName)
    {
    }


    /**
     * {@inheritdoc}
     */
    public function validAuthenticationResponse($user, $result)
    {
    }


    /**
     * {@inheritdoc}
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
    }

}
