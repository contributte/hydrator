<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Adapters;

use WebChemistry\DoctrineHydration\Metadata;

class CallbackFieldAdapter implements IFieldAdapter {

	public function isWorkable(string $field, Metadata $metadata, array $settings): bool {
		return isset($settings['callbacks'][$field]);
	}

	public function work(string $field, $value, Metadata $metadata, array $settings) {
		return $settings['callbacks'][$field]($value);
	}

}
