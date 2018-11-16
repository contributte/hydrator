<?php

use WebChemistry\DoctrineHydration\Adapters\ManyToOneArrayAdapter;
use WebChemistry\DoctrineHydration\Factories\MetadataFactory;
use WebChemistry\DoctrineHydration\Hydration;

class ManyToOneArrayTest extends \Codeception\Test\Unit {

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	/**
	 * @var Hydration
	 */
	protected $hydrator;

	protected function _before() {
		$em = $this->getModule('\Helper\Unit')->createEntityManager();
		$this->hydrator = new Hydration(new MetadataFactory($em));
		$this->hydrator->addArrayAdapter(new ManyToOneArrayAdapter($em));
	}

	protected function _after() {
	}

	// tests
	public function testManyToOne() {
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