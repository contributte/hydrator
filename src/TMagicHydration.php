<?php declare(strict_types = 1);

namespace Nettrine\DoctrineHydration;

trait TMagicHydration
{

	/**
	 * @param mixed $value
	 * @internal
	 */
	public function __set__magicHydration(string $name, $value): void
	{
		$this->$name = $value;
	}

}
