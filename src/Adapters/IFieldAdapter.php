<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Adapters;

use WebChemistry\DoctrineHydration\Arguments\FieldArgs;

interface IFieldAdapter {

	public function isWorkable(FieldArgs $args): bool;

	public function work(FieldArgs $args): void;

}
