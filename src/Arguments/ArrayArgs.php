<?php declare(strict_types = 1);

namespace Nettrine\Hydrator\Arguments;

use Nettrine\Hydrator\Metadata;
use Prowebcraft\Dot;

/**
 * @property-read object $object
 */
final class ArrayArgs extends BaseArgs
{

	/** @var object */
	protected $object;

	/** @var mixed[] */
	protected $values;

	/**
	 * @param mixed[] $values
	 * @param mixed $value
	 * @param mixed[] $settings
	 */
	public function __construct(array &$values, object $object, $value, string $field, Metadata $metadata, array $settings)
	{
		parent::__construct($metadata, $value, $field, $settings);

		$this->object = $object;
		$this->values = &$values;

		// magic
		$this->getters[] = 'object';
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value, ?string $field = null): self
	{
		if ($field) {
			Dot::setValue($this->values, $field, $value);
		} else {
			$this->values[$this->field] = $value;
		}

		return $this;
	}

}
