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

	public static function getFromObject($object, string $name) {
		$getter = 'get' . ucfirst($name);
		$isser = 'is' . ucfirst($name);

		if (method_exists($object, $getter)) {
			return $object->$getter();
		} else if (method_exists($object, $isser)) {
			return $object->$isser();
		} else {
			return $object->$name;
		}
	}

	/**
	 * @param object $object
	 * @param string $name
	 * @param mixed $value
	 */
	public static function injectToObject($object, string $name, $value): void {
		$setter = 'set' . ucfirst($name);
		if (method_exists($object, $setter)) {
			$object->$setter($value);
		} else if (method_exists($object, '__set__MagicHydration')) {
			$object->__set__MagicHydration($name, $value);
		} else {
			$object->$name = $value;
		}
	}

}
