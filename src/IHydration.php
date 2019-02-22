<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration;

use WebChemistry\DoctrineHydration\Adapters\IArrayAdapter;
use WebChemistry\DoctrineHydration\Adapters\IFieldAdapter;

interface IHydration {

	public function addFieldAdapter(IFieldAdapter $adapter);

	public function addArrayAdapter(IArrayAdapter $adapter);

	/**
	 * @param string|object $object
	 * @param mixed[] $values
	 * @param mixed[] $settings
	 */
	public function toFields($object, iterable $values, array $settings = []): object;

	public function toArray(object $object, array $settings = []): array;

}
