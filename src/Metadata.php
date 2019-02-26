<?php declare(strict_types = 1);

namespace Nettrine\Hydrator;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use ReflectionClass;
use ReflectionException;

class Metadata
{

	/** @var ClassMetadata */
	private $metadata;

	/** @var mixed[] */
	private $constructorValues = [];

	/** @var ReflectionClass */
	private $reflection;

	public function __construct(ClassMetadata $classMetadata)
	{
		$this->metadata = $classMetadata;
		$this->reflection = Tools::reflectionClass($classMetadata->getName());
	}

	public function getMetadata(): ClassMetadata
	{
		return $this->metadata;
	}

	public function isManyToOne(string $field): bool
	{
		return $this->metadata->hasAssociation($field) && $this->metadata->associationMappings[$field]['isOwningSide'];
	}

	public function getAssociationTargetClass(string $field): string
	{
		return $this->metadata->getAssociationTargetClass($field);
	}

	/**
	 * @return mixed[]
	 * @throws MappingException
	 */
	public function getFieldMapping(string $field): array
	{
		return $this->metadata->getFieldMapping($field);
	}

	public function isAssociation(string $field): bool
	{
		return isset($this->metadata->associationMappings[$field]);
	}

	/**
	 * @return mixed[]
	 * @throws HydratorException
	 */
	public function getMapping(string $field): array
	{
		if (isset($this->metadata->fieldMappings[$field])) {
			return $this->metadata->fieldMappings[$field];
		}
		if (isset($this->metadata->associationMappings[$field])) {
			return $this->metadata->associationMappings[$field];
		}

		throw new HydratorException(sprintf('Field %s not exists in %s', $field, $this->metadata->getName()));
	}

	/**
	 * @return mixed[]
	 */
	public function getFields(): array
	{
		return array_merge(array_keys($this->metadata->fieldMappings), array_keys($this->metadata->associationMappings));
	}

	/**
	 * @param mixed[] $args
	 */
	public function newInstance(array $args): object
	{
		return $this->reflection->newInstanceArgs($args);
	}

	/**
	 * @return mixed[]
	 * @throws ReflectionException
	 */
	public function getConstructorValues(): array
	{
		if (!$this->constructorValues) {
			$this->constructorValues = Tools::constructorValues($this->metadata->getName());
		}

		return $this->constructorValues;
	}

}
