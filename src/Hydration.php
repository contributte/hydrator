<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration;

use Nette\SmartObject;
use WebChemistry\DoctrineHydration\Adapters\IArrayAdapter;
use WebChemistry\DoctrineHydration\Adapters\IFieldAdapter;
use WebChemistry\DoctrineHydration\Factories\IMetadataFactory;

class Hydration implements IHydration {

	use SmartObject;

	/** @var IMetadataFactory */
	protected $metadataFactory;

	/** @var array */
	protected $values = [];

	/** @var array */
	protected $settings = [];

	/** @var IFieldAdapter[] */
	protected $fieldAdapters = [];

	/** @var IArrayAdapter[] */
	protected $arrayAdapters = [];

	/** @var IPropertyAccessor */
	private $propertyAccessor;

	public function __construct(IMetadataFactory $metadataFactory, ?IPropertyAccessor $propertyAccessor = null) {
		$this->metadataFactory = $metadataFactory;
		$this->propertyAccessor = $propertyAccessor ?: new PropertyAccessor();
	}

	public function addFieldAdapter(IFieldAdapter $adapter) {
		$this->fieldAdapters[] = $adapter;

		return $this;
	}

	public function addArrayAdapter(IArrayAdapter $adapter) {
		$this->arrayAdapters[] = $adapter;

		return $this;
	}

	protected function getValue(string $field) {
		return $this->values[$field];
	}

	/**
	 * @param object|null $object
	 * @param string $field
	 * @param Metadata $metadata
	 * @param object $entity
	 * @return mixed
	 * @throws SkipValueException From adapters for skip
	 */
	protected function getFieldValue($object, string $field, Metadata $metadata, $entity = null) {
		foreach ($this->fieldAdapters as $adapter) {
			if ($adapter->isWorkable($object, $field, $metadata, $this->settings)) {
				return $adapter->work($object, $field, $this->getValue($field), $metadata, $this->settings);
			}
		}

		return $this->values[$field];
	}

	public function toFields($object, iterable $values, array $settings = []) {
		$metadata = $this->metadataFactory->create($object);
		$this->settings = $settings;
		$this->values = Tools::toArray($values);

		// constructor fill
		if (is_string($object)) {
			$constructValues = $metadata->getConstructorValues();
			if ($constructValues) {
				$args = [];
				foreach ($constructValues as list($field, $optional, $default)) {
					if (array_key_exists($field, $this->values)) {
						try {
							$args[] = $this->getFieldValue(null, $field, $metadata);
						} catch (SkipValueException $e) {}

						unset($this->values[$field]);
					} else {
						if ($optional) {
							$args[] = $default;
						} else {
							HydrationException::valueNotExists($field);
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
			if (!array_key_exists($field, $this->values)) {
				continue;
			}
			try {
				$value = $this->getFieldValue($object, $field, $metadata, $object);

				$this->propertyAccessor->set($object, $field, $value);
			} catch (SkipValueException $e) {}
		}

		return $object;
	}

	/**
	 * @param object $object
	 * @param mixed $value
	 * @param string $field
	 * @param Metadata $metadata
	 * @return mixed
	 * @throws SkipValueException
	 */
	protected function getArrayValue($object, $value, string $field, Metadata $metadata) {
		foreach ($this->arrayAdapters as $adapter) {
			if ($adapter->isWorkable($object, $field, $metadata, $this->settings)) {
				$value = $adapter->work($object, $field, $value, $metadata, $this->settings);

				// TODO: rehydrate

				return $value;
			}
		}

		return $value;
	}

	public function toArray($object, array $settings = []): array {
		$metadata = $this->metadataFactory->create($object);
		$this->settings = $settings;

		$values = [];
		foreach ($metadata->getFields() as $field) {
			try {
				$value = $this->propertyAccessor->get($object, $field);

				$values[$field] = $this->getArrayValue($object, $value, $field, $metadata);
			} catch (SkipValueException $e) {}
		}

		return $values;
	}

}
