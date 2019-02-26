<?php declare(strict_types = 1);

namespace Nettrine\Test;

use Nettrine\Hydrator\TMagicHydrator;

class SettersGetters
{

	use TMagicHydrator;

	private $foo = 'foo';

	protected $magic = 'magic';

	public $bar = 'bar';

	protected $bool = true;

	public function setFoo($value): void
	{
		$this->foo = 'fixed';
	}

	/**
	 * @return bool
	 */
	public function isBool(): bool
	{
		return $this->bool;
	}

	/**
	 * @return mixed
	 */
	public function getFoo()
	{
		return $this->foo;
	}

	/**
	 * @return mixed
	 */
	public function getMagic()
	{
		return $this->magic;
	}

}
