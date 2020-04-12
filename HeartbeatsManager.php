<?php

namespace Statamic\Addons\Heartbeats;

use Statamic\Addons\Heartbeats\Exceptions\NonExistingHeartbeatException;
use Statamic\API\File;
use Statamic\API\YAML;
use Statamic\Events\Data\AddonSettingsSaved;

class HeartbeatsManager
{
    protected $storageFilePath;

    protected $heartbeats;

    public function __construct($storagePath)
    {
        $this->storageFilePath = $storagePath;
        $this->loadHeartbeats();
    }

    public function all()
    {
        return collect($this->heartbeats)->map(function($data) {
            return (new Heartbeat())
                ->setName($data['name'])
                ->setFrequency($data['frequency'])
                ->setUrl($data['url']);
        });
    }

    public function flush()
    {
        if (isset($this->heartbeats)) {
            File::put($this->storageFilePath, YAML::dump($this->heartbeats));
            event(new AddonSettingsSaved($this->storageFilePath, $this->heartbeats));
        }
    }

    public function add(Heartbeat $heartbeat)
    {
        $this->heartbeats[$heartbeat->getId()] = $heartbeat->toArray();

        return $this;
    }

    /**
     * @param $id
     * @return Heartbeat
     * @throws NonExistingHeartbeatException
     */
    public function get($id)
    {
        if (false === isset($this->heartbeats[$id])) {
            throw new NonExistingHeartbeatException(sprintf("Heartbeat `%s` (`%s`) doesn't exist!", base64_decode($id), $id));
        }

        $data = $this->heartbeats[$id];

        return (new Heartbeat())
            ->setName($data['name'])
            ->setFrequency($data['frequency'])
            ->setUrl($data['url']);
    }

    public function remove($id)
    {
        unset($this->heartbeats[$id]);

        return $this;
    }

    protected function loadHeartbeats()
    {
        if (!isset($this->heartbeats)) {
            $this->heartbeats = File::exists($this->storageFilePath) ? YAML::parse(File::get($this->storageFilePath)) : [];
        }
    }
}