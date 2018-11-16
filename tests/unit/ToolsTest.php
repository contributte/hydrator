<?php

use WebChemistry\DoctrineHydration\Tools;
use WebChemistry\Test\ConstructorValues;
use WebChemistry\Test\SettersGetters;

class ToolsTest extends \Codeception\Test\Unit {

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before() {
	}

	protected function _after() {
	}

	public function testReflectionClass(): void {
		$reflection = Tools::reflectionClass(self::class);

		$this->assertSame($reflection, Tools::reflectionClass(self::class));
	}

	public function testConstructorValues(): void {
		$values = Tools::constructorValues(ConstructorValues::class);

		$this->assertSame([
			['string', false, null],
			['nullable', false, null],
			['mixed', false, null],
			['default', true, 'foo'],
		], $values);

		$this->assertSame([], Tools::constructorValues('stdClass'));
	}

}