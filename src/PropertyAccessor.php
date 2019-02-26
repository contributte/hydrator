<?php declare(strict_types = 1);

namespace Nettrine\Hydrator;

class PropertyAccessor implements IPropertyAccessor
{

	/**
	 * @return mixed
	 * @throws PropertyAccessException
	 */
	public function get(object $object, string $property)
	{
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

	/**
	 * @param mixed $value
	 * @throws PropertyAccessException
	 */
	public function set(object $object, string $property, $value): void
	{
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

		if (method_exists($object, '__set__magicHydration')) {
			$object->__set__magicHydration($property, $value);
		} else {
			if (!property_exists($object, $property)) {
				throw PropertyAccessException::notExists($object, $property);
			}

			$object->$property = $value;
		}
	}

}
