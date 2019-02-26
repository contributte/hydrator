<?php declare(strict_types = 1);

namespace Nettrine\Hydrator;

use Exception;

class PropertyAccessException extends Exception
{

	public static function notExists(object $object, string $property): self
	{
		return new self(sprintf('Property %s not exists in %s', $property, get_class($object)));
	}

}
