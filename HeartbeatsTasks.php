<?php

namespace Statamic\Addons\Heartbeats;

use Statamic\Extend\Tasks;
use Illuminate\Console\Scheduling\Schedule;

class HeartbeatsTasks extends Tasks
{
    /**
     * Define the task schedule
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    public function schedule(Schedule $schedule)
    {
        $heartbeatsManager = app(HeartbeatsManager::class);
        $heartbeats = $heartbeatsManager->all();
        foreach ($heartbeats as $heartbeat) {
            $this->setScheduleCommandForHeartbeat($schedule, $heartbeat);
        }
    }

    protected function setScheduleCommandForHeartbeat(Schedule $schedule, Heartbeat $heartbeat)
    {
        $command = $schedule->command(sprintf('heartbeats:send "%s"', $heartbeat->getName()));

        switch ($heartbeat->getFrequency())
        {
            case '10_minutes':
                $command->everyTenMinutes();
                break;
            case '30_minutes':
                $command->everyThirtyMinutes();
                break;
            case '1_hour':
                $command->hourly();
                break;
            case '12_hours':
                $command->twiceDaily();
                break;
            case '1_day':
                $command->daily();
                break;
            case '1_week':
                $command->weekly();
                break;
            default:
                $command->hourly();
        }

    }
}
