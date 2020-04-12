<?php

namespace Statamic\Addons\Heartbeats;

class Heartbeat
{
    /** @var string  $name */
    protected $name;

    /** @var string $frequency */
    protected $frequency;

    /** @var string $url */
    protected $url;

    public function getId()
    {
        return base64_encode($this->name);
    }

    /**
     * @param string $name
     * @return Heartbeat
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $frequency
     * @return Heartbeat
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param string $url
     * @return Heartbeat
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}