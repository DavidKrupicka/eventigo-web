<?php

namespace App\Components;

use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
use Nette\Utils\DateTime;


abstract class BaseControl extends Control
{
	/** @var \Kdyby\Translation\Translator */
	protected $translator;


	function __construct(Translator $translator)
	{
		$this->translator = $translator;
	}


	protected function createTemplate()
	{
		$template = parent::createTemplate();

		if ($file = $this->getTemplateDefaultFile()) {
			$template->setFile($file);
		}

		$template->addFilter('datetime', function (DateTime $a, DateTime $b = null) {
			// Set locale to get translated day name
			setlocale(LC_TIME, ($this->translator->getLocale() === 'cs') ? 'cs_CZ' : null);
			$aDayName = mb_convert_case(strftime('%A ', $a->getTimestamp()), MB_CASE_TITLE, 'UTF-8');

			// Two day event
			if ($b && ($a->format('dmy') !== $b->format('dmy'))) {
				$result = $aDayName . $a->format('j. n. ') . '&nbsp;&ndash;&nbsp;' . $b->format('j. n. Y');
			}
			// One day event
			else {
				$result = $aDayName . $a->format('j. n. Y');
				// Add Hour:minute time if its not 00:00
				if ((int)$a->format('G') > 0) {
					$result .= $a->format(' G:i');
				}
			}
			return $result;
		});

		return $template;
	}


	/**
	 * Derives template path from class name.
	 *
	 * @return null|string
	 */
	protected function getTemplateDefaultFile()
	{
		$refl = $this->getReflection();
		$file = dirname($refl->getFileName()) . '/' . $refl->getShortName() . '.latte';
		return file_exists($file) ? $file : NULL;
	}


	public function render()
	{
		$this->template->render();
	}
}
