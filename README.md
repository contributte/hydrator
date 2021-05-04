![](https://heatbadger.now.sh/github/readme/contributte/hydrator/?deprecated=1)

<p align=center>
    <a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
    <a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
    <a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/sponsor/donations/F96854"></a>
</p>

<p align=center>
    Website 🚀 <a href="https://contributte.org">contributte.org</a> | Contact 👨🏻‍💻 <a href="https://f3l1x.io">f3l1x.io</a> | Twitter 🐦 <a href="https://twitter.com/contributte">@contributte</a>
</p>

## Disclaimer

| :warning: | This project is no longer being maintained.
|---|---|

| Composer | [`contributte/hydrator`](https://packagist.org/packages/contributte/hydrator) |
|---| --- |
| Version | ![](https://badgen.net/packagist/v/contributte/hydrator) |
| PHP | ![](https://badgen.net/packagist/php/contributte/hydrator) |
| License | ![](https://badgen.net/github/license/contributte/hydrator) |

## Usage

### Nette installation

```mneon
extensions:
    hydrator: Nettrine\Hydrator\DI\HydratorExtension
```

### Basic usage

```php
$entity = $hydrator->toFields(Entity::class, [
	'name' => 'foo',
	'field' => 'value',
]);

$entity = $hydrator->toFields($entityObj, [
	'name' => 'foo',
	'field' => 'value',
]);
```

### Entity to an array

```php
$array = $hydrator->toArray($entity);
```

### Custom ArrayAccessor

Used to read from or write to an object's property.

```php
class CustomPropertyAccessor implements IPropertyAccessor {
	
	public function get(object $object, string $property) { ... }
	
	public function set(object $object, string $property, $value): void { ... }
	
}
```

Nette registration:
```neon
hydrator:
    propertyAccessor: CustomPropertyAccessor
```

### Adapters

Do you have custom rules of getting or setting an object's value? The existing features don't suit your needs? Adapters can be used to extend the functionality.

All the adapters have to be registered via `addFieldAdapter` or `addArrayAdapter` methods.

In Nette:

```neon
hydrator:
    adapters:
        fields:
            - Nettrine\DoctrineHydration\Adapters\CallbackFieldAdapter
            - Nettrine\DoctrineHydration\Adapters\TargetEntityFieldAdapter
        array:
            - Nettrine\DoctrineHydration\Adapters\JoinArrayAdapter
            - Nettrine\DoctrineHydration\Adapters\ManyToOneAdapter

```

#### ArrayAdapter

`IArrayAdapter` interface is implemented. Built-in adapters:

##### ManyToOneArrayAdapter

All object relations are converted to an ID.

```php
$entity = new Assoc class {
	public $id = 42;
	
	public $foo = 'foo';
	
	/**
	 * @ManyToOne(targetEntity="Assoc")
	 */
	public $assoc;
};

$entity->assoc->id++;

$array = $hydrator->toArray($entity);

$array === [
	'id' => 42,
	'assoc' => 43,
];
```

##### JoinArrayAdapter

Object association is converted to an array.

```php
$entity = new Assoc class {
	public $id = 42;
	
	public $foo = 'foo';
	
	/**
	 * @ManyToOne(targetEntity="Assoc")
	 */
	public $assoc;
};

$entity->assoc->id++;

$array = $hydrator->toArray($entity, [
	'joins' => [
		'assoc' => 'foo'
	]
]);

$array === [
	'id' => 42,
	'assoc' => 'foo',
];
```

#### FieldAdapter

`IFieldAdapter` interface is implemented. Built-in adapters:

##### CallbackFieldAdapter

A callback can be used:

```php
$hydrator->toFields($obj, [
	'name' => 'foo',
], [
	'callbacks' => [
		'name' => function (FieldArgs $args) {
		    $args->value = ucfirst($args->value);
		},
	] 
]);
```

The value of the `$name` property is now `Foo`.

##### TargetEntityFieldAdapter

In case of an association the corresponding entity will be found:

```php
$hydrator->toFields($obj, [
	'assoc' => 42, // Item with the value of 42 will be found
]);
```

#### Creating a custom adapter

Say we have the following `image` custom type annotation:

```php
/**
 * @ORM\Column(type="image")
 */
```

and we want to automatically save the image during hydration:

```php

class CustomFieldAdapter implements IFieldAdapter {

	public function __construct(IImageStorage $storage) { ... }

	public function isWorkable(FieldArgs $args): bool {
		// Apply only when the type is `image` and it is not an assocation
		return !$args->metadata->isAssociation($field) && $args->metadata->getFieldMapping($field)['type'] === 'image';
	}

	public function work(FieldArgs $args): void {
		$image = new Image($value);
		if ($args->hasSettingsSection('images')) {
			$image->setName($args->getSettingsSection('images'));
		}
		$this->storage->save($image);
		
		$args->value = $image;
	}

}

```

Registration in Nette:

```neon
hydrator:
    adapters:
        fields: 
            - CustomFieldAdapter
```

Usage:

```php
$hydrator->toFields($obj, [
	'avatar' => __DIR__ . '/avatar.png',
], [
	'images' => [
		'avatar' => 'foo.png',
	]
]);
```

## Development

This package was maintain by these authors.

<a href="https://github.com/f3l1x">
  <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=80">
</a>

<a href="https://github.com/Gappa">
  <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/749981?v=3&s=80">
</a>

-----

Consider to [support](https://contributte.org/partners.html) **contributte** development team.
Also thank you for being used this package.
