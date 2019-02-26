<?php declare(strict_types = 1);

namespace Nettrine\Hydrator\Arguments;

use Nette\SmartObject;
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
	public function __construct(Metadata $metadata, $value, string $field, array $settings)
	{
		$this->metadata = $metadata;
		$this->settings = $settings;
		$this->field = $field;
		$this->value = $value;
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
