<?php

use Nettrine\Hydrator\Adapters\CallbackFieldAdapter;
use Nettrine\Hydrator\Factories\MetadataFactory;
use Nettrine\Hydrator\Hydrator;
use Nettrine\Hydrator\SkipValueException;

class CallbackFieldTest extends \Codeception\Test\Unit
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
		$this->hydrator->addFieldAdapter(new CallbackFieldAdapter());
	}

	protected function _after()
	{
	}

	// tests
	public function testCallback()
	{
		/** @var Simple $obj */
		$obj = $this->hydrator->toFields(Simple::class, [
			'name' => 'foo',
			'position' => 'bar',
			'nullable' => 15,
		], [
			'callbacks' => [
				'name' => function ($value) {
					$this->assertSame('foo', $value);

					return 'bar';
				},
			],
		]);

		$this->assertSame($obj->getName(), 'bar');
	}

}
