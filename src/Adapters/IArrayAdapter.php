<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Adapters;

use WebChemistry\DoctrineHydration\Metadata;

interface IArrayAdapter {

	public function isWorkable(string $field, Metadata $metadata, array $settings): bool;

	public function work(string $field, $value, Metadata $metadata, array $settings);

}
