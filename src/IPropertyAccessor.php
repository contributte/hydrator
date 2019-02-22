<?php declare(strict_types = 1);

namespace Nettrine\DoctrineHydration;

interface IPropertyAccessor
{

	/**
	 * @return mixed
	 */
	public function get(object $object, string $property);

	/**
	 * @param mixed $value
	 */
	public function set(object $object, string $property, $value): void;

}
