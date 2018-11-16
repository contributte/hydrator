<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Factories;

use Doctrine\ORM\EntityManagerInterface;
use Nette\SmartObject;
use WebChemistry\DoctrineHydration\Metadata;

class MetadataFactory implements IMetadataFactory {

	use SmartObject;

	/** @var EntityManagerInterface */
	private $em;

	/** @var Metadata[] */
	private $cache = [];

	public function __construct(EntityManagerInterface $em) {
		$this->em = $em;
	}

	/**
	 * @param string|object $object
	 * @return Metadata
	 */
	public function create($object): Metadata {
		$name = is_object($object) ? get_class($object) : $object;
		if (!isset($this->cache[$name])) {
			$this->cache[$name] = new Metadata($this->em->getClassMetadata($name));
		}

		return $this->cache[$name];
	}

}
