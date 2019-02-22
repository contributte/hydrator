<?php declare(strict_types = 1);

namespace Nettrine\DoctrineHydration;

use Nette\StaticClass;
use ReflectionClass;
use ReflectionException;
use Traversable;

final class Tools
{

	use StaticClass;

	/** @var mixed[] */
	private static $cache = [];

	/** @var ReflectionClass[] */
	private static $reflectionCache = [];

	/**
	 * @param mixed[] $values
	 * @return mixed[]
	 */
	public static function toArray(iterable $values): array
	{
		if ($values instanceof Traversable) {
			$values = iterator_to_array($values);
		}

		return $values;
	}

	public static function reflectionClass(string $object): ReflectionClass
	{
		if (!isset(self::$reflectionCache[$object])) {
			self::$reflectionCache[$object] = new ReflectionClass($object);
		}

		return self::$reflectionCache[$object];
	}

	/**
	 * @return mixed[]
	 * @throws ReflectionException
	 */
	public static function constructorValues(string $object): array
	{
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
