<?php declare(strict_types = 1);

namespace Nettrine\Hydrator\Arguments;

use Nette\SmartObject;
use Nettrine\Hydrator\IHydrator;
use Nettrine\Hydrator\Metadata;

/**
 * @property-read Metadata $metadata
 * @property-read mixed $value
 * @property-read string $field
 * @property-read array $settings
 */
abstract class BaseArgs
{

	use SmartObject {
		SmartObject::__get as private smartObjectGet;
		SmartObject::__set as private smartObjectSet;
	}

	/** @var string[] */
	protected $getters = ['metadata', 'settings', 'field', 'value'];

	/** @var string[] */
	protected $setters = [];

	/** @var IHydrator */
	private $hydrator;

	/** @var Metadata */
	protected $metadata;

	/** @var mixed[] */
	protected $settings;

	/** @var string */
	protected $field;

	/** @var mixed */
	protected $value;

	/**
	 * @param mixed $value
	 * @param mixed[] $settings
	 */
	public function __construct(IHydrator $hydrator, Metadata $metadata, $value, string $field, array $settings)
	{
		$this->hydrator = $hydrator;
		$this->metadata = $metadata;
		$this->settings = $settings;
		$this->field = $field;
		$this->value = $value;
	}

	/**
	 * @param string|object $object
	 * @param mixed[] $values
	 * @param mixed[] $settings
	 */
	public function hydrateToFields($object, iterable $values, array $settings = []): object
	{
		return $this->hydrator->toFields($object, $values, $settings);
	}

	/**
	 * @param mixed[] $settings
	 * @return mixed[]
	 */
	public function hydrateToArray(object $object, array $settings = []): array
	{
		return $this->hydrator->toArray($object, $settings);
	}

	public function hasSettingsSection(string $section): bool
	{
		return isset($this->settings[$section][$this->field]);
	}

	/**
	 * @return mixed
	 */
	public function getSettingsSection(string $section)
	{
		return $this->settings[$section][$this->field];
	}

	public function getMetadata(): Metadata
	{
		return $this->metadata;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	public function getField(): string
	{
		return $this->field;
	}

	/**
	 * @return mixed
	 */
	public function __get(string $name)
	{
		if (in_array($name, $this->getters)) {
			$getter = 'get' . ucfirst($name);
			if (method_exists($this, $getter)) {
				return $this->$getter();
			} else {
				return $this->$name;
			}
		}

		return $this->smartObjectGet($name);
	}

	/**
	 * @param mixed $value
	 */
	public function __set(string $name, $value): void
	{
		if (in_array($name, $this->setters)) {
			$setter = 'set' . ucfirst($name);
			if (method_exists($this, $setter)) {
				$this->$setter($value);
			} else {
				$this->$name = $value;
			}
		} else {
			$this->smartObjectSet($name, $value);
		}
	}

}
