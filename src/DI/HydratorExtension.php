<?php declare(strict_types = 1);

namespace Nettrine\Hydrator\DI;

use Nette\DI\CompilerExtension;
use Nettrine\Hydrator\Adapters\IArrayAdapter;
use Nettrine\Hydrator\Adapters\IFieldAdapter;
use Nettrine\Hydrator\Factories\IMetadataFactory;
use Nettrine\Hydrator\Factories\MetadataFactory;
use Nettrine\Hydrator\Hydration;
use Nettrine\Hydrator\IHydration;
use Nettrine\Hydrator\IPropertyAccessor;
use Nettrine\Hydrator\PropertyAccessor;

class HydratorExtension extends CompilerExtension
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
