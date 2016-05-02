<?php

namespace App\Modules\Front\Presenters;

use App\Modules\Core\Model\UserModel;
use Nette;
use Nette\Utils\DateTime;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends \App\Modules\Core\Presenters\BasePresenter
{
	/** @var UserModel @inject */
	public $userModel;

	/** @var DateTime */
	protected $lastAccess;


	protected function startup()
	{
		parent::startup();

		$this->updateAccess();
	}


	private function updateAccess()
	{
		$access = $this->getSession('access');
		$now = new DateTime;

		// Get last access stored in DB
		if ($this->getUser()->isLoggedIn()) {
			$user = $this->userModel->getAll()
				->wherePrimary($this->getUser()->getId())
				->fetch();
			$lastInDb = $user->last_access;
		}

		// Store the newest access
		$this->lastAccess = \App\Modules\Core\Utils\DateTime::max($access->last ?? null, $lastInDb ?? null);

		// Update last access
		$access->last = clone $now;

		// Update last access in DB if it has been updated few minutes ago or earlier
		$syncToDb = $this->getParameters()['lastAccess']['syncToDb'] ?? '5 minutes';
		if (!($access->lastInDb ?? null)
			|| (($lastInDb ?? null) && $lastInDb > $access->lastInDb)
			|| $access->lastInDb < new DateTime('-' . $syncToDb)) {

			if ($this->getUser()->isLoggedIn()) {
				$this->userModel->getAll()
					->wherePrimary($this->getUser()->getId())
					->update([
						'last_access' => $now,
					]);
			}

			// Update last in DB access
			$access->lastInDb = clone $now;
		}
	}

}