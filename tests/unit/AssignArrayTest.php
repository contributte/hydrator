<?php

use Nettrine\Hydrator\Adapters\AssignArrayAdapter;
use Nettrine\Hydrator\Factories\MetadataFactory;
use Nettrine\Hydrator\Hydrator;

class AssignArrayTest extends \Codeception\Test\Unit
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
		$this->hydrator->addArrayAdapter(new AssignArrayAdapter());
	}

	protected function _after()
	{
	}

	// tests
	public function testAdapter()
	{
		$simple = new Simple('foo', 'bar');

		$array = $this->hydrator->toArray($simple, [
			'assigns' => [
				'name' => 'assign',
			],
		]);

		$this->assertSame([
			'id' => null,
			'name' => 'foo',
			'assign' => 'foo',
			'position' => 'bar',
			'nullable' => null,
		], $array);
	}

}