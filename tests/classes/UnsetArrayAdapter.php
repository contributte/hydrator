<?php declare(strict_types = 1);

namespace Nettrine\Test;

use Nettrine\Hydrator\Adapters\IArrayAdapter;
use Nettrine\Hydrator\Arguments\ArrayArgs;

final class UnsetArrayAdapter implements IArrayAdapter {

	public function isWorkable(ArrayArgs $args): bool {
		return $args->hasSettingsSection('unset');
	}

	public function work(ArrayArgs $args): void {
		$args->unsetValue();
	}

}
