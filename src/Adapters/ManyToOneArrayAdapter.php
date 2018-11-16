<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Adapters;

use Doctrine\ORM\EntityManagerInterface;
use WebChemistry\DoctrineHydration\Metadata;
use WebChemistry\DoctrineHydration\Tools;

class ManyToOneArrayAdapter implements IArrayAdapter {

	/** @var EntityManagerInterface */
	private $em;

	public function __construct(EntityManagerInterface $em) {
		$this->em = $em;
	}

	public function isWorkable(string $field, Metadata $metadata, array $settings): bool {
		return $metadata->isManyToOne($field);
	}

	public function work(string $field, $value, Metadata $metadata, array $settings) {
		if (!is_object($value)) {
			return $value;
		}

		$metadata = $this->em->getClassMetadata($metadata->getAssociationTargetClass($field));
		$id = $metadata->getIdentifier()[0];

		return Tools::getFromObject($value, $id);
	}

}
