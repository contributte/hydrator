<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Adapters;

use WebChemistry\DoctrineHydration\Arguments\FieldArgs;

class CallbackFieldAdapter implements IFieldAdapter {

	public function isWorkable(FieldArgs $args): bool {
		return $args->hasSettingsSection('callbacks');
	}

	public function work(FieldArgs $args): void {
		$args->value = $args->getSettingsSection('callbacks')($args->value, $args);
	}

}
