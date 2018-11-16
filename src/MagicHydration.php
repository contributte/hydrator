<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration;

trait MagicHydration {

	/**
	 * @internal
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set__MagicHydration(string $name, $value): void {
		$this->$name = $value;
	}

}
