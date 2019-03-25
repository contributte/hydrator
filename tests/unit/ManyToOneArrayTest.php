<?php

use Nettrine\Hydrator\Adapters\ManyToOneArrayAdapter;
use Nettrine\Hydrator\Factories\MetadataFactory;
use Nettrine\Hydrator\Hydrator;
use Nettrine\Hydrator\PropertyAccessor;

class ManyToOneArrayTest extends \Codeception\Test\Unit
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
		$this->hydrator->addArrayAdapter(new ManyToOneArrayAdapter($em, new PropertyAccessor()));
	}

	protected function _after()
	{
	}

	// tests
	public function testManyToOne()
	{
		$simple = new Simple('foo', 'bar');
		$simple->setId(1);
		$manyToOne = new ManyToOne($simple);

		$array = $this->hydrator->toArray($manyToOne);
		$this->assertSame([
			'id' => null,
			'simple' => 1,
		], $array);
	}

}