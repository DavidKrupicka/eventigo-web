<?php

namespace App\Modules\Front\Components\EventsList;


interface EventsListFactory
{
	/**
	 * @param array $events
	 * @return EventsList
	 */
	public function create(array $events);
}