<?php

use Doctrine\ORM\EntityManagerInterface;
use WebChemistry\DoctrineHydration\Adapters\TargetEntityFieldAdapter;
use WebChemistry\DoctrineHydration\Factories\MetadataFactory;
use WebChemistry\DoctrineHydration\Hydration;
use WebChemistry\DoctrineHydration\Metadata;

class TargetEntityFieldTest extends \Codeception\Test\Unit {

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	/**
	 * @var Hydration
	 */
	protected $hydrator;

	public $called;

	public $obj;

	protected function _before() {
		$em = $this->getModule('\Helper\Unit')->createEntityManager();
		$this->hydrator = new Hydration(new MetadataFactory($em));
		$this->hydrator->addFieldAdapter(new class($em, $this) extends TargetEntityFieldAdapter {

			private $self;

			public function __construct(EntityManagerInterface $em, $self) {
				parent::__construct($em);
				$this->self = $self;
			}

			public function work(string $field, $value, Metadata $metadata, array $settings) {
				$this->self->called = [$field, $value];

				return $this->self->obj = new Simple('foo', 'bar');
			}
		});
	}

	protected function _after() {
	}

	// tests
	public function testTargetEntity() {
		/** @var ManyToOne $obj */
		$obj = $this->hydrator->toFields(ManyToOne::class, [
			'simple' => 15,
		]);

		$this->assertNotNull($this->called);
		$this->assertSame(['simple', 15], $this->called);
		$this->assertSame($obj->getSimple(), $this->obj);
	}

}
