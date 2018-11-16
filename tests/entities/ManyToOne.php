<?php declare(strict_types = 1);

use Doctrine\ORM\Mapping as ORM;
use WebChemistry\DoctrineHydration\MagicHydration;

/**
 * @Entity()
 */
class ManyToOne {

	use MagicHydration;

	/**
	 * @var int
	 * @Column(type="integer", nullable=FALSE)
	 * @Id
	 * @GeneratedValue
	 */
	private $id;

	/**
	 * @var Simple
	 * @ManyToOne(targetEntity="Simple")
	 */
	protected $simple;

	public function __construct(?Simple $simple = null) {
		$this->simple = $simple;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return Simple
	 */
	public function getSimple(): Simple {
		return $this->simple;
	}

}
