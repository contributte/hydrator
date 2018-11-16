<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Adapters;

use WebChemistry\DoctrineHydration\Metadata;
use WebChemistry\DoctrineHydration\Tools;

class JoinArrayAdapter implements IArrayAdapter {

	public function isWorkable(string $field, Metadata $metadata, array $settings): bool {
		return isset($settings['joins'][$field]);
	}

	public function work(string $field, $value, Metadata $metadata, array $settings) {
		if (!is_object($value)) {
			return $value;
		}

		return Tools::getFromObject($value, $settings['joins'][$field]);
	}

}
