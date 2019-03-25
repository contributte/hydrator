<?php

use Nettrine\Hydrator\Factories\MetadataFactory;
use Nettrine\Hydrator\Hydrator;

class FieldsTest extends \Codeception\Test\Unit
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
	}

	protected function _after()
	{
	}

	private function getField($object, string $field)
	{
		$values = [];
		foreach ((array) $object as $name => $value) {
			if (strpos($name, "\0") !== false) {
				$name = substr($name, strrpos($name, "\0") + 1);
			}
			$values[$name] = $value;
		}

		return $values[$field];
	}

	// tests
	public function testConstructorValues()
	{
		/** @var Simple $obj */
		$obj = $this->hydrator->toFields(Simple::class, [
			'name' => 'foo',
			'position' => 'bar',
		]);

		$this->assertSame('foo', $this->getField($obj, 'name'));
		$this->assertSame('bar', $this->getField($obj, 'position'));
		$this->assertSame(null, $this->getField($obj, 'nullable'));
	}

	public function testConstructorValuesFillNullable()
	{
		/** @var Simple $obj */
		$obj = $this->hydrator->toFields(Simple::class, [
			'name' => 'foo',
			'position' => 'bar',
			'nullable' => 15,
		]);

		$this->assertSame('foo', $this->getField($obj, 'name'));
		$this->assertSame('bar', $this->getField($obj, 'position'));
		$this->assertSame(15, $this->getField($obj, 'nullable'));
	}

}
