<?php

namespace mkiselev\broadcasting\broadcasters;

use mkiselev\broadcasting\helpers\StrHelper;
use yii\base\Component;
use yii\web\ForbiddenHttpException;

abstract class Broadcaster extends Component implements BroadcasterInterface
{
    /**
     * The registered channel authenticators.
     *
     * @var array
     */
    public $channels;

    /**
     * Register a channel authenticator.
     *
     * @param string $channel
     * @param callable $callback
     * @return $this
     */
    public function channel($channel, callable $callback)
    {
        $this->channels[$channel] = $callback;

        return $this;
    }


    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param \yii\web\User $user
     * @param string $channel
     * @return mixed
     * @throws \yii\web\ForbiddenHttpException
     */
    protected function verifyUserCanAccessChannel($user, $channel)
    {
        foreach ($this->channels as $pattern => $callback) {

            if (!StrHelper::is(preg_replace('/\{(.*?)\}/', '*', $pattern), $channel)) {
                continue;
            }

            $parameters = $this->extractAuthParameters($pattern, $channel, $callback);

            if ($result = $callback($user, ...$parameters)) {
                return $this->validAuthenticationResponse($user, $result);
            }
        }

        throw new ForbiddenHttpException(403);
    }


    /**
     * Extract the parameters from the given pattern and channel.
     *
     * @param string $pattern
     * @param string $channel
     * @param callable $callback
     * @return array
     */
    protected function extractAuthParameters($pattern, $channel, $callback)
    {
        $channelKeys = $this->extractChannelKeys($pattern, $channel);

        return array_filter($channelKeys, function ($value, $key) {
            return is_numeric($key);
        }, ARRAY_FILTER_USE_BOTH);
    }


    /**
     * Extract the channel keys from the incoming channel name.
     *
     * @param  string $pattern
     * @param  string $channel
     * @return array
     */
    protected function extractChannelKeys($pattern, $channel)
    {
        preg_match('/^' . preg_replace('/\{(.*?)\}/', '(?<$1>[^\.]+)', $pattern) . '/', $channel, $keys);

        return $keys;
    }


    /**
     * Format the channel array into an array of strings.
     *
     * @param  array $channels
     * @return array
     */
    protected function formatChannels(array $channels)
    {
        return array_map(function ($channel) {
            return (string)$channel;
        }, $channels);
    }

}
