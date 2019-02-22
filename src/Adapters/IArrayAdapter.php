<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Adapters;

use WebChemistry\DoctrineHydration\Arguments\ArrayArgs;

interface IArrayAdapter {

	public function isWorkable(ArrayArgs $args): bool;

	public function work(ArrayArgs $args): void;

}
