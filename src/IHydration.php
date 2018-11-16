<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration;

use WebChemistry\DoctrineHydration\Adapters\IArrayAdapter;
use WebChemistry\DoctrineHydration\Adapters\IFieldAdapter;

interface IHydration {

	public function addFieldAdapter(IFieldAdapter $adapter);

	public function addArrayAdapter(IArrayAdapter $adapter);

	public function toFields($object, iterable $values, array $settings = []);

	public function toArray($object, array $settings = []): array;

}
