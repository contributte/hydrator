<?php declare(strict_types = 1);

namespace WebChemistry\DoctrineHydration\Factories;

use WebChemistry\DoctrineHydration\Metadata;

interface IMetadataFactory {

	public function create($object): Metadata;

}
