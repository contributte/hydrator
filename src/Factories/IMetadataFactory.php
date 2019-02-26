<?php declare(strict_types = 1);

namespace Nettrine\Hydrator\Factories;

use Nettrine\Hydrator\Metadata;

interface IMetadataFactory
{

	/**
	 * @param object|string $object
	 */
	public function create($object): Metadata;

}
