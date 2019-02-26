<?php declare(strict_types = 1);

namespace Nettrine\Hydrator\Adapters;

use Nettrine\Hydrator\Arguments\FieldArgs;

interface IFieldAdapter
{

	public function isWorkable(FieldArgs $args): bool;

	public function work(FieldArgs $args): void;

}
