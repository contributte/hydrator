<?php

use Nettrine\Hydrator\Adapters\IArrayAdapter;
use Nettrine\Hydrator\Factories\MetadataFactory;
use Nettrine\Hydrator\Hydrator;
use Nettrine\Test\UnsetArrayAdapter;

class ArrayTest extends \Codeception\Test\Unit
{

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	/**
	 * @var Hydrator
	 */
	protected $hydrator;

	protected function _before()
	{
		$em = $this->getModule('\Helper\Unit')->createEntityManager();
		$this->hydrator = new Hydrator(new MetadataFactory($em));
		$this->hydrator->addArrayAdapter(new UnsetArrayAdapter());
	}

	protected function _after()
	{
	}

	// tests
	public function testSimple()
	{
		/** @var Simple $obj */
		$obj = $this->hydrator->toFields(Simple::class, [
			'name' => 'foo',
			'position' => 'bar',
			'nullable' => 15,
		]);

		$this->assertSame([
			'id' => null,
			'name' => 'foo',
			'position' => 'bar',
			'nullable' => 15,
		], $this->hydrator->toArray($obj));
	}

	public function testArrayUnset()
	{
		/** @var Simple $obj */
		$obj = $this->hydrator->toFields(Simple::class, [
			'name' => 'foo',
			'position' => 'bar',
			'nullable' => 15,
		]);

		$this->assertSame([
			'id' => null,
			'name' => 'foo',
			'position' => 'bar',
		], $this->hydrator->toArray($obj, [
			'unset' => [
				'nullable' => true,
			]
		]));
	}

}
