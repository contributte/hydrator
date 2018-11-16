<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration;

interface IPropertyAccessor {

	/**
	 * @param object $object
	 * @param string $property
	 * @return mixed
	 */
	public function get($object, string $property);

	/**
	 * @param object $object
	 * @param string $property
	 * @param mixed $value
	 */
	public function set($object, string $property, $value): void;

}
