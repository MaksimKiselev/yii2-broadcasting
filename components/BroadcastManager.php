<?php

namespace mkiselev\broadcasting\components;

use mkiselev\broadcasting\events\BroadcastEvent;
use mkiselev\broadcasting\Module;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * @property-read string|null Socket ID for the current request
 */
class BroadcastManager extends Component
{
    /**
     * Dispatch event
     *
     * @param \mkiselev\broadcasting\events\BroadcastEvent $event
     */
    public function dispatchEvent(BroadcastEvent $event)
    {
        $name = $event->broadcastAs();

        $channels = ArrayHelper::toArray($event->broadcastOn());

        $payload = $event->broadcastWith();

        if ($event->toOthers === true) {
            array_merge($payload, ['socket' => $this->getSocketId()]);
        }

        Module::getInstance()->getBroadcasterInstance()->broadcast($channels, $name, $payload);
    }

    /**
     * Get the socket ID for the current request
     *
     * @return string|null
     */
    public function getSocketId()
    {
        $request = Yii::$app->getRequest();
        if ($request instanceof yii\web\Request) {
            return $request->getHeaders()->get('X-Socket-ID');
        }

        return null;
    }

}
