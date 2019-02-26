<?php

use Nettrine\Hydrator\PropertyAccessor;
use Nettrine\Test\SettersGetters;

class PropertyAccessorTest extends \Codeception\Test\Unit
{

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before()
	{
	}

	protected function _after()
	{
	}

	// tests
	public function testGetters()
	{
		$obj = new SettersGetters();
		$accessor = new PropertyAccessor();

		$this->assertSame('foo', $accessor->get($obj, 'foo'));
		$this->assertSame(true, $accessor->get($obj, 'bool'));
		$this->assertSame('bar', $accessor->get($obj, 'bar'));
	}
}