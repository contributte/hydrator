<?php declare(strict_types = 1);

namespace Nettrine\DoctrineHydration\Adapters;

use Nettrine\DoctrineHydration\Arguments\ArrayArgs;

interface IArrayAdapter
{

	public function isWorkable(ArrayArgs $args): bool;

	public function work(ArrayArgs $args): void;

}
