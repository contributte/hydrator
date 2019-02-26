<?php declare(strict_types = 1);

namespace Nettrine\Hydrator;

use Nettrine\Hydrator\Adapters\IArrayAdapter;
use Nettrine\Hydrator\Adapters\IFieldAdapter;

interface IHydration
{

	/**
	 * @return static
	 */
	public function addFieldAdapter(IFieldAdapter $adapter);

	/**
	 * @return static
	 */
	public function addArrayAdapter(IArrayAdapter $adapter);

	/**
	 * @param string|object $object
	 * @param mixed[] $values
	 * @param mixed[] $settings
	 */
	public function toFields($object, iterable $values, array $settings = []): object;

	/**
	 * @param mixed[] $settings
	 * @return mixed[]
	 */
	public function toArray(object $object, array $settings = []): array;

}
