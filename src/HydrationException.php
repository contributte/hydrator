<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration;

class HydrationException extends \Exception {

	public static function checkObject($object) {
		if (!is_string($object) && !is_object($object)) {
			throw new self(sprintf('Given object must be string or object, %s given', gettype($object)));
		}
	}

	/**
	 * @param string $name
	 * @throws HydrationException
	 */
	public static function valueNotExists(string $name): void {
		throw new self("Value for item '$name' not exists.");
	}

	/**
	 * @param string $name
	 * @throws HydrationException
	 */
	public static function cannotInjectParameter(string $name): void {
		throw new self("Cannot inject value to parameter '$name'.");
	}

	/**
	 * @param string $name
	 * @throws HydrationException
	 */
	public static function cannotGetParameter(string $name): void {
		throw new self("Cannot get value from parameter '$name'.");
	}

}
