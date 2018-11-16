<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration;

class PropertyAccessException extends \Exception {

	public static function notExists($object, string $property): self {
		return new self("Property '$property' not exists in " . get_class($object));
	}

}
