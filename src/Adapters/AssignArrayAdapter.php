<?php declare(strict_types = 1);

namespace Nettrine\Hydrator\Adapters;

use Nettrine\Hydrator\Arguments\ArrayArgs;

class AssignArrayAdapter implements IArrayAdapter
{

	public function isWorkable(ArrayArgs $args): bool
	{
		return $args->hasSettingsSection('assigns');
	}

	public function work(ArrayArgs $args): void
	{
		$args->setValue($args->value, $args->getSettingsSection('assigns'));
	}

}
