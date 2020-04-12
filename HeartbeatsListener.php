<?php

namespace Statamic\Addons\Heartbeats;

use Statamic\API\Nav;
use Statamic\Extend\Listener;

class HeartbeatsListener extends Listener
{
    /**
     * The events to be listened for, and the methods to call.
     *
     * @var array
     */
    public $events = [
        'cp.nav.created' => 'addNavItem',
    ];

    public function addNavItem($nav)
    {
        $nav->addTo('tools', Nav::item('Heartbeats')
            ->icon('heart')
            ->route('heartbeats.index'));
    }
}
