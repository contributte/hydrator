<?php declare(strict_types = 1);

use Doctrine\ORM\Mapping as ORM;
use Nettrine\Hydrator\TMagicHydrator;

/**
 * @Entity()
 */
class ManyToOne
{

	use TMagicHydrator;

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

	public function __construct(?Simple $simple = null)
	{
		$this->simple = $simple;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return Simple
	 */
	public function getSimple(): Simple
	{
		return $this->simple;
	}

}
