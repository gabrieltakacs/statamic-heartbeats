## Installation
1. Download addon and extract it to `site/addons/Heartbeats` directory.

## How it works
This module pings web monitoring services in defined intervals. You can add a new heartbeat in control panel tool by clicking 
on `Create heartbeat` button in the right top corner. For each heartbeat, you can set following parameters:
* Name - name of heartbeat only for internal identification in case more than one heartbeat is set
* Frequency - how often will be heartbeat sent (monitoring service pinged)
* URL - monitoring service URL which will be pinged

Heartbeats data is stored in `site/storage/addons/heartbeats/heartbeats.yaml`.

Heartbeats can be also manually sent by running `php please heartbeats:send {heartbeat_name}` command (for debugging purposes).