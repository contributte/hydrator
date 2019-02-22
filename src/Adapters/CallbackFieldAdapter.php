<?php declare(strict_types = 1);

namespace Nettrine\DoctrineHydration\Adapters;

use Nettrine\DoctrineHydration\Arguments\FieldArgs;

class CallbackFieldAdapter implements IFieldAdapter
{

	public function isWorkable(FieldArgs $args): bool
	{
		return $args->hasSettingsSection('callbacks');
	}

	public function work(FieldArgs $args): void
	{
		$args->value = $args->getSettingsSection('callbacks')($args->value, $args);
	}

}
