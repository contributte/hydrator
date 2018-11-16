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
	 * @param string $field
	 * @param Metadata $metadata
	 * @return mixed
	 */
	protected function getFieldValue(string $field, Metadata $metadata) {
		foreach ($this->fieldAdapters as $adapter) {
			if ($adapter->isWorkable($field, $metadata, $this->settings)) {
				return $adapter->work($field, $this->getValue($field), $metadata, $this->settings);
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
						$args[] = $this->getFieldValue($field, $metadata);

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
			$value = $this->getFieldValue($field, $metadata);

			$this->propertyAccessor->set($object, $field, $value);
		}

		return $object;
	}

	/**
	 * @param mixed $value
	 * @param string $field
	 * @param Metadata $metadata
	 * @return mixed
	 * @throws SkipValueException
	 */
	protected function getArrayValue($value, string $field, Metadata $metadata) {
		foreach ($this->arrayAdapters as $adapter) {
			if ($adapter->isWorkable($field, $metadata, $this->settings)) {
				$value = $adapter->work($field, $value, $metadata, $this->settings);

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

				$values[$field] = $this->getArrayValue($value, $field, $metadata);
			} catch (SkipValueException $e) {}
		}

		return $values;
	}

}
