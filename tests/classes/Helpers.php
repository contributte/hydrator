<?php declare(strict_types = 1);

namespace WebChemistry\Test;

use ReflectionProperty;

final class Helpers {

	public static function resetProperty($object, string $property, $value) {
		$reflectionProperty = new ReflectionProperty($object, $property);

		$reflectionProperty->setAccessible(true);
		$reflectionProperty->setValue($object, $value);
	}

}
