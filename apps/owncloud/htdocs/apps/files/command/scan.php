<?php
/**
 * Copyright (c) 2013 Thomas Müller <thomas.mueller@tmit.eu>
 * Copyright (c) 2013 Bart Visscher <bartv@thisnet.nl>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Files\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Scan extends Command {

	/**
	 * @var \OC\User\Manager $userManager
	 */
	private $userManager;

	public function __construct(\OC\User\Manager $userManager) {
		$this->userManager = $userManager;
		parent::__construct();
	}

	protected function configure() {
		$this
			->setName('files:scan')
			->setDescription('rescan filesystem')
			->addArgument(
					'user_id',
					InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
					'will rescan all files of the given user(s)'
				     )
			->addOption(
					'all',
					null,
					InputOption::VALUE_NONE,
					'will rescan all files of all known users'
				   )
		;
	}

	protected function scanFiles($user, OutputInterface $output) {
		$scanner = new \OC\Files\Utils\Scanner($user);
		$scanner->listen('\OC\Files\Utils\Scanner', 'scanFile', function($path) use ($output) {
			$output->writeln("Scanning <info>$path</info>");
		});
		$scanner->listen('\OC\Files\Utils\Scanner', 'scanFolder', function($path) use ($output) {
			$output->writeln("Scanning <info>$path</info>");
		});
		$scanner->scan('');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		\OC_App::loadApps('authentication');
		if ($input->getOption('all')) {
			$users = $this->userManager->search('');
		} else {
			$users = $input->getArgument('user_id');
		}

		foreach ($users as $user) {
			if (is_object($user)) {
				$user = $user->getUID();
			}
			$this->scanFiles($user, $output);
		}
	}
}
