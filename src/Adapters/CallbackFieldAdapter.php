<?php declare(strict_types = 1);

namespace Nettrine\Hydrator\Adapters;

use Nettrine\Hydrator\Arguments\FieldArgs;

class CallbackFieldAdapter implements IFieldAdapter
{

	public function isWorkable(FieldArgs $args): bool
	{
		return $args->hasSettingsSection('callbacks');
	}

	public function work(FieldArgs $args): void
	{
		$args->getSettingsSection('callbacks')($args);
	}

}
