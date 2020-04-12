<?php

namespace Statamic\Addons\Heartbeats\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Statamic\Addons\Heartbeats\Exceptions\NonExistingHeartbeatException;
use Statamic\Addons\Heartbeats\HeartbeatsManager;
use Statamic\Extend\Command;

class HeartbeatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'heartbeats:send {name : Heartbeat name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send heartbeat';

    protected $heartbeatsManager;

    /**
     * Create a new command instance.
     */
    public function __construct(HeartbeatsManager $heartbeatsManager)
    {
        $this->heartbeatsManager = $heartbeatsManager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        try {
            $heartbeat = $this->heartbeatsManager->get(base64_encode($name));
        } catch (NonExistingHeartbeatException $e) {
            $this->error($e->getMessage());
            exit;
        }

        $client = new Client();

        try {
            $response = $client->request('GET', $heartbeat->getUrl());

            if ($response->getStatusCode() == 200) {
                $this->info(sprintf('Successfully sent heartbeat to `%s`.', $heartbeat->getUrl()));
            } else {
                $this->warn(sprintf('Heartbeat sent to `%s` returned HTTP code %s.', $heartbeat->getUrl(), $response->getStatusCode()));
            }
        } catch (GuzzleException $e) {
            $this->error(sprintf('Error sending heartbeat to `%s`: `%s`', $heartbeat->getUrl(), $e->getMessage()));
        }
    }
}
