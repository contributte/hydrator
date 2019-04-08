<?php declare(strict_types = 1);

namespace Nettrine\Hydrator\Arguments;

use Nettrine\Hydrator\IHydrator;
use Nettrine\Hydrator\Metadata;

/**
 * @property-read object|null $object
 * @property mixed $value
 * @property-read mixed[] $values
 */
final class FieldArgs extends BaseArgs
{

	/** @var object|null */
	protected $object;

	/** @var mixed[] */
	private $values;

	/**
	 * @param mixed[] $values
	 * @param mixed[] $settings
	 */
	public function __construct(IHydrator $hydrator, ?object $object, string $field, array $values, Metadata $metadata, array $settings)
	{
		parent::__construct($hydrator, $metadata, $values[$field], $field, $settings);

		$this->object = $object;
		$this->values = $values;

		// magic
		$this->setters[] = 'value';
		$this->getters[] = 'object';
		$this->getters[] = 'values';
	}

	/**
	 * @return mixed[]
	 */
	public function getValues(): array
	{
		return $this->values;
	}

}
