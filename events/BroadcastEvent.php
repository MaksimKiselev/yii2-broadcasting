<?php

namespace mkiselev\broadcasting\events;

use mkiselev\broadcasting\Module;
use ReflectionClass;
use ReflectionProperty;
use Yii;
use yii\base\Object;

/**
 * @property bool toOthers
 */
abstract class BroadcastEvent extends Object
{
    /*
     * Is it necessary to exclude the current user from the broadcast's recipients
     */
    private $_toOthers = false;

    /**
     * @param bool $value
     * @return $this
     */
    public function toOthers($value = true)
    {
        $this->_toOthers = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getToOthers()
    {
        return $this->_toOthers;
    }

    /**
     * Get the channels the event should broadcast on
     *
     * @return string|array
     */
    abstract public function broadcastOn();

    /**
     * The event's broadcast name
     *
     * @return string
     */
    public function broadcastAs()
    {
        return str_replace('\\', '.', static::class);
    }

    /**
     * Get the data to broadcast
     *
     * @return array
     */
    public function broadcastWith()
    {
        $class = new ReflectionClass($this);
        $data = [];
        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $name = $property->getName();
                $data[$name] = $property->getValue($this);
            }
        }

        return $data;
    }

    /**
     * Broadcast this event
     */
    final public function broadcast()
    {
        try {
            Module::getInstance()->getBroadcastManagerInstance()->dispatchEvent($this);
        } catch (\Exception $e) {
            Yii::error($e);
        }
    }

}
