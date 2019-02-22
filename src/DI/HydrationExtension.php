<?php declare(strict_types = 1);

namespace Nettrine\DoctrineHydration\DI;

use Nette\DI\CompilerExtension;
use Nettrine\DoctrineHydration\Adapters\IArrayAdapter;
use Nettrine\DoctrineHydration\Adapters\IFieldAdapter;
use Nettrine\DoctrineHydration\Factories\IMetadataFactory;
use Nettrine\DoctrineHydration\Factories\MetadataFactory;
use Nettrine\DoctrineHydration\Hydration;
use Nettrine\DoctrineHydration\IHydration;
use Nettrine\DoctrineHydration\IPropertyAccessor;
use Nettrine\DoctrineHydration\PropertyAccessor;

class HydrationExtension extends CompilerExtension
{

	/** @var mixed[] */
	public $defaults = [
		'adapters' => [
			'fields' => [],
			'array' => [],
		],
		'propertyAccessor' => PropertyAccessor::class,
	];

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		$hydration = $builder->addDefinition($this->prefix('hydration'))
			->setType(IHydration::class)
			->setFactory(Hydration::class);

		$builder->addDefinition($this->prefix('metadataFactory'))
			->setType(IMetadataFactory::class)
			->setFactory(MetadataFactory::class);

		$builder->addDefinition($this->prefix('propertyAccessor'))
			->setType(IPropertyAccessor::class)
			->setFactory($config['propertyAccessor']);

		foreach ($config['adapters']['fields'] as $name => $adapter) {
			if (class_exists($adapter)) {
				$def = $builder->addDefinition($this->prefix('fieldAdapter.' . $name))
					->setType(IFieldAdapter::class)
					->setFactory($adapter);
			} else {
				$def = $adapter;
			}

			$hydration->addSetup('addFieldAdapter', [$def]);
		}

		foreach ($config['adapters']['array'] as $name => $adapter) {
			if (class_exists($adapter)) {
				$def = $builder->addDefinition($this->prefix('arrayAdapter.' . $name))
					->setType(IArrayAdapter::class)
					->setFactory($adapter);
			} else {
				$def = $adapter;
			}

			$hydration->addSetup('addArrayAdapter', [$def]);
		}
	}

}
