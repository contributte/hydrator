<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Arguments;

use WebChemistry\DoctrineHydration\Metadata;

/**
 * @property-read object|null $object
 * @property mixed $value
 */
final class FieldArgs extends ArgsAbstract {

	/** @var object|null */
	protected $object;

	/** @var mixed[] */
	private $values;

	/**
	 * @param mixed[] $values
	 * @param mixed[] $settings
	 */
	public function __construct(?object $object, string $field, array $values, Metadata $metadata, array $settings) {
		parent::__construct($metadata, $values[$field], $field, $settings);

		$this->object = $object;
		$this->values = $values;

		// magic
		$this->setters[] = 'value';
		$this->getters[] = 'object';
	}

}
