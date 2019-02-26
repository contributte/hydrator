<?php declare(strict_types = 1);

namespace Nettrine\Hydrator\Adapters;

use Nettrine\Hydrator\Arguments\ArrayArgs;

interface IArrayAdapter
{

	public function isWorkable(ArrayArgs $args): bool;

	public function work(ArrayArgs $args): void;

}
