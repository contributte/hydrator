<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Adapters;

use WebChemistry\DoctrineHydration\IPropertyAccessor;
use WebChemistry\DoctrineHydration\Metadata;

class JoinArrayAdapter implements IArrayAdapter {

	/** @var IPropertyAccessor */
	private $propertyAccessor;

	public function __construct(IPropertyAccessor $propertyAccessor) {
		$this->propertyAccessor = $propertyAccessor;
	}

	public function isWorkable($object, string $field, Metadata $metadata, array $settings): bool {
		return isset($settings['joins'][$field]);
	}

	public function work($object, string $field, $value, Metadata $metadata, array $settings) {
		if (!is_object($value)) {
			return $value;
		}

		return $this->propertyAccessor->get($value, $settings['joins'][$field]);
	}

}
