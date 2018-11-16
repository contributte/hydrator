<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration;

use Doctrine\ORM\Mapping\ClassMetadata;

class Metadata {

	/** @var ClassMetadata */
	private $metadata;

	/** @var array */
	private $constructorValues = [];

	/** @var \ReflectionClass */
	private $reflection;

	public function __construct(ClassMetadata $classMetadata) {
		$this->metadata = $classMetadata;
		$this->reflection = Tools::reflectionClass($classMetadata->getName());
	}

	public function getMetadata(): ClassMetadata {
		return $this->metadata;
	}

	public function isManyToOne(string $field): bool {
		return $this->metadata->hasAssociation($field) && $this->metadata->associationMappings[$field]['isOwningSide'];
	}

	public function getAssociationTargetClass(string $field): string {
		return $this->metadata->getAssociationTargetClass($field);
	}

	public function getFieldMappings(string $field): array {
		if (isset($this->metadata->fieldMappings[$field])) {
			return $this->metadata->fieldMappings[$field];
		}
		if (isset($this->metadata->associationMappings[$field])) {
			return $this->metadata->associationMappings[$field];
		}

		throw new HydrationException("Field $field not exists in " . $this->metadata->getName());
	}

	public function getFields(): array {
		return array_merge(array_keys($this->metadata->fieldMappings), array_keys($this->metadata->associationMappings));
	}

	public function newInstance(array $args) {
		return $this->reflection->newInstanceArgs($args);
	}

	public function getConstructorValues(): array {
		if (!$this->constructorValues) {
			$this->constructorValues = Tools::constructorValues($this->metadata->getName());
		}

		return $this->constructorValues;
	}

}
