<?php declare(strict_types = 1);

namespace Nettrine\DoctrineHydration\Factories;

use Nettrine\DoctrineHydration\Metadata;

interface IMetadataFactory
{

	/**
	 * @param object|string $object
	 */
	public function create($object): Metadata;

}
