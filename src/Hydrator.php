<?php declare(strict_types = 1);

namespace Nettrine\Hydrator;

use Nette\SmartObject;
use Nettrine\Hydrator\Adapters\IArrayAdapter;
use Nettrine\Hydrator\Adapters\IFieldAdapter;
use Nettrine\Hydrator\Arguments\ArrayArgs;
use Nettrine\Hydrator\Arguments\FieldArgs;
use Nettrine\Hydrator\Factories\IMetadataFactory;

class Hydrator implements IHydrator
{

	use SmartObject;

	/** @var IMetadataFactory */
	protected $metadataFactory;

	/** @var IFieldAdapter[] */
	protected $fieldAdapters = [];

	/** @var IArrayAdapter[] */
	protected $arrayAdapters = [];

	/** @var IPropertyAccessor */
	private $propertyAccessor;

	public function __construct(IMetadataFactory $metadataFactory, ?IPropertyAccessor $propertyAccessor = null)
	{
		$this->metadataFactory = $metadataFactory;
		$this->propertyAccessor = $propertyAccessor ?: new PropertyAccessor();
	}

	public function addFieldAdapter(IFieldAdapter $adapter): self
	{
		$this->fieldAdapters[] = $adapter;

		return $this;
	}

	public function addArrayAdapter(IArrayAdapter $adapter): self
	{
		$this->arrayAdapters[] = $adapter;

		return $this;
	}

	/**
	 * @param mixed[] $settings
	 * @return mixed[]
	 * @throws PropertyAccessException
	 */
	public function toArray(object $object, array $settings = []): array
	{
		$metadata = $this->metadataFactory->create($object);

		$values = [];
		foreach ($metadata->getFields() as $field) {
			$value = $this->propertyAccessor->get($object, $field);

			$this->getArrayValue($values, $object, $value, $field, $metadata, $settings);
		}

		return $values;
	}

	/**
	 * @param string|object $object
	 * @param mixed[] $values
	 * @param mixed[] $settings
	 * @throws HydratorException
	 * @throws PropertyAccessException
	 */
	public function toFields($object, iterable $values, array $settings = []): object
	{
		$metadata = $this->metadataFactory->create($object);
		$values = Tools::toArray($values);

		// constructor fill
		if (is_string($object)) {
			$constructValues = $metadata->getConstructorValues();
			if ($constructValues) {
				$args = [];
				foreach ($constructValues as [$field, $optional, $default]) {
					if (array_key_exists($field, $values)) {
						$args[] = $this->getFieldValue(null, $field, $metadata, $values, $settings);

						unset($values[$field]);
					} else {
						if ($optional) {
							$args[] = $default;
						} else {
							HydratorException::valueNotExists($field);
						}
					}
				}
				$object = $metadata->newInstance($args);
			} else {
				$object = new $object();
			}
		}

		// mappings
		foreach ($metadata->getFields() as $field) {
			if (!array_key_exists($field, $values)) {
				continue;
			}
			$value = $this->getFieldValue($object, $field, $metadata, $values, $settings);

			$this->propertyAccessor->set($object, $field, $value);
		}

		return $object;
	}

	/**
	 * @param mixed[] $values
	 * @param mixed[] $settings
	 * @return mixed
	 */
	protected function getFieldValue(?object $object, string $field, Metadata $metadata, array $values, array $settings)
	{
		$value = $values[$field];
		foreach ($this->fieldAdapters as $adapter) {
			$args = new FieldArgs($this, $object, $field, $values, $metadata, $settings);
			if ($adapter->isWorkable($args)) {
				$adapter->work($args);
				$value = $args->value;

				if ($value instanceof RecursiveHydration) {
					$value = $this->toFields($value->getObject(), $value->getValues(), $value->getSettings());
				}

				break;
			}
		}

		return $value;
	}

	/**
	 * @param mixed[] $values
	 * @param mixed $value
	 * @param mixed[] $settings
	 */
	protected function getArrayValue(array &$values, object $object, $value, string $field, Metadata $metadata, array $settings): void
	{
		$values[$field] = $value;
		foreach ($this->arrayAdapters as $adapter) {
			$args = new ArrayArgs($this, $values, $object, $value, $field, $metadata, $settings);
			if ($adapter->isWorkable($args)) {
				$adapter->work($args);

				// TODO: rehydrate
			}
		}
	}

}
