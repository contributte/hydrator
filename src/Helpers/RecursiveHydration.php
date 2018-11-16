<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Helpers;

use Nette\SmartObject;

class RecursiveHydration {

	use SmartObject;

	/** @var object|string */
	private $object;

	/** @var array */
	private $values;

	/** @var array */
	private $settings;

	public function __construct($object, array $values, array $settings = []) {
		$this->object = $object;
		$this->values = $values;
		$this->settings = $settings;
	}

	/**
	 * @return object|string
	 */
	public function getObject() {
		return $this->object;
	}

	/**
	 * @return array
	 */
	public function getSettings(): array {
		return $this->settings;
	}

	/**
	 * @return array
	 */
	public function getValues(): array {
		return $this->values;
	}

}
