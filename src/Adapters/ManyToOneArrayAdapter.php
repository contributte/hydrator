<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Adapters;

use Doctrine\ORM\EntityManagerInterface;
use WebChemistry\DoctrineHydration\IPropertyAccessor;
use WebChemistry\DoctrineHydration\Metadata;
use WebChemistry\DoctrineHydration\Tools;

class ManyToOneArrayAdapter implements IArrayAdapter {

	/** @var EntityManagerInterface */
	private $em;

	/** @var IPropertyAccessor */
	private $propertyAccessor;

	public function __construct(EntityManagerInterface $em, IPropertyAccessor $propertyAccessor) {
		$this->em = $em;
		$this->propertyAccessor = $propertyAccessor;
	}

	public function isWorkable($object, string $field, Metadata $metadata, array $settings): bool {
		return $metadata->isManyToOne($field);
	}

	public function work($object, string $field, $value, Metadata $metadata, array $settings) {
		if (!is_object($value)) {
			return $value;
		}

		$metadata = $this->em->getClassMetadata($metadata->getAssociationTargetClass($field));
		$id = $metadata->getIdentifier()[0];

		return $this->propertyAccessor->get($value, $id);
	}

}
