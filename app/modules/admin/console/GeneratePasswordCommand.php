<?php

namespace App\Modules\Admin\Console;

use App\Modules\Core\Model\UserModel;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class GeneratePasswordCommand extends Command
{
	protected function configure()
	{
		$this->setName('admin:generatePassword')
			->setDescription('Create newsletters')
			->addArgument(
				'password',
				InputArgument::REQUIRED,
				'Raw password'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$password = (string)$input->getArgument('password');

		/** @var UserModel $userModel */
		$userModel = $this->getHelper('container')->getByType(UserModel::class);

		$hash = $userModel->hashAndEncrypt($password);

		$output->writeLn($hash);
		return 0;
	}
}