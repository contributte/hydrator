<?php

use Doctrine\ORM\EntityManagerInterface;
use Nettrine\Hydrator\Adapters\TargetEntityFieldAdapter;
use Nettrine\Hydrator\Arguments\FieldArgs;
use Nettrine\Hydrator\Factories\MetadataFactory;
use Nettrine\Hydrator\Hydration;
use Nettrine\Test\Helpers;

class TargetEntityFieldTest extends \Codeception\Test\Unit
{

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	/**
	 * @var Hydration
	 */
	protected $hydrator;

	private $em;

	public $called;

	public $obj;

	protected function _before()
	{
		$this->em = $em = $this->getModule('\Helper\Unit')->createEntityManager();
		$this->hydrator = new Hydration(new MetadataFactory($em));
		$this->hydrator->addFieldAdapter(new class($em, $this) extends TargetEntityFieldAdapter
		{

			private $self;

			public function __construct(EntityManagerInterface $em, $self)
			{
				parent::__construct($em);
				$this->self = $self;
			}

			public function work(FieldArgs $args): void
			{
				$this->self->called = [$args->field, $args->value];

				$args->value = $this->self->obj = new Simple('foo', 'bar');
			}
		});
	}

	protected function _after()
	{
	}

	// tests
	public function testTargetEntity()
	{
		/** @var ManyToOne $obj */
		$obj = $this->hydrator->toFields(ManyToOne::class, [
			'simple' => 15,
		]);

		$this->assertNotNull($this->called);
		$this->assertSame(['simple', 15], $this->called);
		$this->assertSame($obj->getSimple(), $this->obj);
	}

	public function testArray()
	{
		Helpers::resetProperty($this->hydrator, 'fieldAdapters', []);
		$this->hydrator->addFieldAdapter(new TargetEntityFieldAdapter($this->em));

		/** @var ManyToOne $obj */
		$obj = $this->hydrator->toFields(ManyToOne::class, [
			'simple' => [
				'name' => 'foo',
				'position' => 'bar',
			],
		]);

		$simple = $obj->getSimple();
		$this->assertInstanceOf(Simple::class, $simple);
		$this->assertSame('foo', $simple->getName());
		$this->assertSame('bar', $simple->getPosition());
	}

}
