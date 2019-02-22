<?php declare(strict_types = 1);

namespace Nettrine\DoctrineHydration\Adapters;

use Nettrine\DoctrineHydration\Arguments\FieldArgs;

interface IFieldAdapter
{

	public function isWorkable(FieldArgs $args): bool;

	public function work(FieldArgs $args): void;

}
