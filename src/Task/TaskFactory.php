<?php

namespace ShineUnited\ComposerBuildPlugin\Scss\Task;

use ShineUnited\ComposerBuild\Capability\TaskFactory as TaskFactoryCapability;


class TaskFactory implements TaskFactoryCapability {

	public function handlesType($type) {
		$types = array(
			'sass',
			'scss'
		);

		return in_array($type, $types);
	}

	public function createTask($type, $name, array $config = array()) {
		switch($type) {
			case 'sass':
			case 'scss':
				return new ScssTask($name, $config);
			default:
				return false;
		}
	}
}
