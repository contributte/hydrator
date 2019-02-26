## Entity to array and conversely

[![Build Status](https://travis-ci.org/Nettrine/hydrator.svg?branch=master)](https://travis-ci.org/Nettrine/hydrator)

## Nette instalace

```yaml
extensions:
    hydrator: Nettrine\Hydrator\DI\HydratorExtension
```

## Základní použití

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

## Entity na pole

```php
$array = $hydrator->toArray($entity);
```

## Vlastní ArrayAccessor

Slouží k získání hodnoty z objektu nebo zapsání hodnoty do objektu.

```php
class CustomPropertyAccessor implements IPropertyAccessor {
	
	public function get(object $object, string $property) { ... }
	
	public function set(object $object, string $property, $value): void { ... }
	
}
```

Nette registrace:
```yaml
hydrator:
    propertyAccessor: CustomPropertyAccessor
```

## Adaptéry

Máte vlastní pravidla pro získání nebo zapsání hodnoty do objektu? Nebo vám nestačí současná
funkcionalita? Můžete je rozšířit přes adaptery. 

Všechny adaptéry se musí zaregistrovat přes addFieldAdapter nebo addArrayAdapter metody.

V nette:

```yaml
hydrator:
    adapters:
        fields:
            - Nettrine\DoctrineHydration\Adapters\CallbackFieldAdapter
            - Nettrine\DoctrineHydration\Adapters\TargetEntityFieldAdapter
        array:
            - Nettrine\DoctrineHydration\Adapters\JoinArrayAdapter
            - Nettrine\DoctrineHydration\Adapters\ManyToOneAdapter

```

### ArrayAdapter

Implementují rozhraní IArrayAdapter. Vestavěné adaptéry:

#### ManyToOneArrayAdapter
Všechny objektové asociace převede na ID.

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

#### JoinArrayAdapter
Objektovou asociaci převede na dané pole

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

### FieldAdapter

Implementují rozhraní IFieldAdapter. Vestavěné adaptéry:


#### CallbackFieldAdapter
Můžeme použít vlastní callback na pole:

```php
$hydrator->toFields($obj, [
	'name' => 'foo',
], [
	'callbacks' => [
		'name' => function ($value) {
			return ucfirst($value);
		},
	] 
]);
```

Hodnota property $name bude nyní Foo.

#### TargetEntityFieldAdapter
Pokud se jedná o asociaci, tak se najde entita:

```php
$hydrator->toFields($obj, [
	'assoc' => 42, // najde se položka s hodnotou 42
]);
```

### Tvorba vlastního adapteru

Máme svojí anotaci image 

```php
/**
 * @ORM\Column(type="image")
 */
```

a chceme automaticky ukládat obrázky při hydrataci

```php

class CustomFieldAdapter implements IFieldAdapter {

	public function __construct(IImageStorage $storage) { ... }

	public function isWorkable(FieldArgs $args): bool {
		// funguj jen když typ je image a není asociace
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

Registrace v nette:

```yaml
hydrator:
    adapters:
        fields: 
            - CustomFieldAdapter
```

Použití:

```php
$hydrator->toFields($obj, [
	'avatar' => __DIR__ . '/avatar.png',
], [
	'images' => [
		'avatar' => 'foo.png',
	]
]);
```
