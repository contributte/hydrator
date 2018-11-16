<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration;

use ReflectionClass;

class Tools {

	/** @var array */
	private static $cache = [];

	/** @var ReflectionClass[] */
	private static $reflectionCache = [];

	public static function toArray(iterable $values): array {
		if ($values instanceof \Traversable) {
			$values = iterator_to_array($values);
		}

		return $values;
	}

	public static function reflectionClass(string $object): ReflectionClass {
		if (!isset(self::$reflectionCache[$object])) {
			self::$reflectionCache[$object] = new ReflectionClass($object);
		}

		return self::$reflectionCache[$object];
	}

	public static function constructorValues(string $object): array {
		if (!isset(self::$cache[$object])) {
			self::$cache[$object] = [];

			$constructor = self::reflectionClass($object)->getConstructor();

			if (!$constructor) {
				return [];
			}

			foreach ($constructor->getParameters() as $parameter) {
				self::$cache[$object][] = [
					$parameter->getName(),
					$parameter->isOptional(),
					$parameter->isOptional() ? $parameter->getDefaultValue() : null,
				];
			}
		}

		return self::$cache[$object];
	}

}
