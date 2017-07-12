<?php

namespace mkiselev\broadcasting\channels;

class PrivateChannel extends Channel
{
    /**
     * Create a new channel instance
     *
     * @param  string $name
     */
    public function __construct($name)
    {
        parent::__construct('private-' . $name);
    }
}
