<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Adapters;

use Doctrine\ORM\EntityManagerInterface;
use WebChemistry\DoctrineHydration\Helpers\RecursiveHydration;
use WebChemistry\DoctrineHydration\Metadata;

class TargetEntityFieldAdapter implements IFieldAdapter {

	/** @var EntityManagerInterface */
	private $em;

	public function __construct(EntityManagerInterface $em) {
		$this->em = $em;
	}

	public function isWorkable($object, string $field, Metadata $metadata, array $settings): bool {
		return isset($metadata->getMapping($field)['targetEntity']);
	}

	public function work($object, string $field, $value, Metadata $metadata, array $settings) {
		$targetEntity = $metadata->getMapping($field)['targetEntity'];

		if ($value instanceof $targetEntity) {
			return $value;
		}
		if ($value === null) {
			return null;
		}
		if (is_array($value)) {
			// TODO: settings

			return new RecursiveHydration($targetEntity, $value);
		}

		return $this->em->getRepository($targetEntity)->find($value);
	}

}
