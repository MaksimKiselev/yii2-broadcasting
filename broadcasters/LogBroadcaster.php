<?php

namespace mkiselev\broadcasting\broadcasters;

use Yii;

class LogBroadcaster extends Broadcaster
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
        $channels = implode(', ', $this->formatChannels($channels));

        $payload = json_encode($payload, JSON_PRETTY_PRINT);

        $massage = 'Broadcasting [' . $event . '] on channels [' . $channels . '] with payload:' . PHP_EOL . $payload;

        Yii::info($massage, __METHOD__);
    }

}
