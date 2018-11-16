<?php

use WebChemistry\DoctrineHydration\Adapters\JoinArrayAdapter;
use WebChemistry\DoctrineHydration\Factories\MetadataFactory;
use WebChemistry\DoctrineHydration\Hydration;

class JoinArrayTest extends \Codeception\Test\Unit {

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
		$this->hydrator->addArrayAdapter(new JoinArrayAdapter());
	}

	protected function _after() {
	}

	// tests
	public function testSomeFeature() {
		$simple = new Simple('foo', 'bar');
		$simple->setId(1);
		$manyToOne = new ManyToOne($simple);

		$array = $this->hydrator->toArray($manyToOne, [
			'joins' => [
				'simple' => 'name',
			],
		]);

		$this->assertSame([
			'id' => null,
			'simple' => 'foo',
		], $array);
	}
}