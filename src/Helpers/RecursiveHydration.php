<?php declare(strict_types = 1);

namespace Nettrine\DoctrineHydration\Helpers;

use Nette\SmartObject;

class RecursiveHydration
{

	use SmartObject;

	/** @var object|string */
	private $object;

	/** @var mixed[] */
	private $values;

	/** @var mixed[] */
	private $settings;

	/**
	 * @param object|string $object
	 * @param mixed[] $values
	 * @param mixed[] $settings
	 */
	public function __construct($object, array $values, array $settings = [])
	{
		$this->object = $object;
		$this->values = $values;
		$this->settings = $settings;
	}

	/**
	 * @return object|string
	 */
	public function getObject()
	{
		return $this->object;
	}

	/**
	 * @return mixed[]
	 */
	public function getSettings(): array
	{
		return $this->settings;
	}

	/**
	 * @return mixed[]
	 */
	public function getValues(): array
	{
		return $this->values;
	}

}
