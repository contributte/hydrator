<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Factories;

use WebChemistry\DoctrineHydration\Metadata;

interface IMetadataFactory {

	/**
	 * @param object|string $object
	 */
	public function create($object): Metadata;

}
