<?php

namespace ShineUnited\ComposerBuildPlugin\Scss\Task;

use ShineUnited\ComposerBuild\Task\Task;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\IO\IOInterface;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;


class ScssTask extends Task {

	public function configure() {

		$this->addArgument(
			'src', // name
			InputArgument::REQUIRED, // mode
			'Source file path', // description
			null // default
		);

		$this->addArgument(
			'dest', // name
			InputArgument::REQUIRED, // mode
			'Destination file path', // description
			null // default
		);

		$this->addOption(
			'compress',
			null,
			InputOption::VALUE_NONE,
			'Compress the css output',
			null
		);

		$this->addOption(
			'map',
			null,
			InputOption::VALUE_REQUIRED,
			'Path to map file (coming soon...)',
			false
		);
	}

	public function execute(InputInterface $input, OutputInterface $output) {
		$io = $this->getIO();

		$scss = new Compiler();

		if($input->getOption('compress')) {
			$io->write('Setting SCSS compiler output to <comment>compressed</comment>', true, IOInterface::VERBOSE);
			$scss->setOutputStyle(OutputStyle::COMPRESSED);
		} else {
			$io->write('Setting SCSS compiler output to <comment>expanded</comment>', true, IOInterface::VERBOSE);
			$scss->setOutputStyle(OutputStyle::EXPANDED);
		}

		$workingDirPath = getcwd();

		$srcPath = $input->getArgument('src');
		$destPath = $input->getArgument('dest');

		$io->write('Setting SCSS import path to <info>' . $workingDirPath . '</info>', true, IOInterface::VERBOSE);
		$scss->setImportPaths($workingDirPath);

		$io->write('Compiling <info>' . $srcPath . '</info>', true, IOInterface::NORMAL);
		$output = $scss->compile('@import "' . $srcPath . '"', $srcPath);

		$destDirPath = dirname($destPath);
		if(!is_dir($destDirPath)) {
			$io->write('Creating directory <info>' . $destDirPath . '</info>', true, IOInterface::VERBOSE);
			mkdir($destDirPath, 0777, true);
		}

		$io->write('Writing compiled CSS to <info>' . $destPath . '</info>', true, IOInterface::NORMAL);
		file_put_contents($destPath, $output);
	}
}
