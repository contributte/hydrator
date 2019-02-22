<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Adapters;

use WebChemistry\DoctrineHydration\Arguments\ArrayArgs;

class AssignArrayAdapter implements IArrayAdapter {

	public function isWorkable(ArrayArgs $args): bool {
		return $args->hasSettingsSection('assigns');
	}

	public function work(ArrayArgs $args): void {
		$args->setValue($args->value, $args->getSettingsSection('assigns'));
	}

}
