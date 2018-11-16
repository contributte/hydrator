<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration;

class PropertyAccessor implements IPropertyAccessor {

	public function get($object, string $property) {
		$ucfirst = ucfirst($property);
		$methods = [
			'get' . $ucfirst,
			'is' . $ucfirst,
			'has' . $ucfirst,
		];

		foreach ($methods as $method) {
			if (method_exists($object, $method)) {
				return $object->$method();
			}
		}

		if (!property_exists($object, $property)) {
			throw PropertyAccessException::notExists($object, $property);
		}

		return $object->$property;
	}

	public function set($object, string $property, $value): void {
		$ucfirst = ucfirst($property);
		$methods = [
			'set' . $ucfirst,
		];
		foreach ($methods as $method) {
			if (method_exists($object, $method)) {
				$object->$method($value);

				return;
			}
		}

		if (method_exists($object, '__set__MagicHydration')) {
			$object->__set__MagicHydration($property, $value);
		} else {
			if (!property_exists($object, $property)) {
				throw PropertyAccessException::notExists($object, $property);
			}

			$object->$property = $value;
		}
	}

}
