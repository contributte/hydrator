<?php declare(strict_types = 1);

use Doctrine\ORM\Mapping as ORM;
use Nettrine\Hydrator\TMagicHydrator;

/**
 * @Entity()
 */
class ManyToMany
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
	 * @var Simple[]
	 * @ManyToMany(targetEntity="Simple")
	 */
	protected $simples;

}
