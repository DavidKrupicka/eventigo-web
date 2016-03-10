<?php

namespace App\Modules\Admin\Components\EventForm;


interface EventFormFactory
{
	/**
	 * @return EventForm
	 */
	public function create();
}