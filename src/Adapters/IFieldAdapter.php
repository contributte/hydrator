<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Adapters;

use WebChemistry\DoctrineHydration\Metadata;

interface IFieldAdapter {

	/**
	 * @param object|null $object
	 * @param string $field
	 * @param Metadata $metadata
	 * @param array $settings
	 * @return bool
	 */
	public function isWorkable($object, string $field, Metadata $metadata, array $settings): bool;

	/**
	 * @param object|null $object
	 * @param string $field
	 * @param mixed $value
	 * @param Metadata $metadata
	 * @param array $settings
	 * @return mixed
	 */
	public function work($object, string $field, $value, Metadata $metadata, array $settings);

}
