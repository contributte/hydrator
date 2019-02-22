<?php declare(strict_types = 1);

namespace Nettrine\DoctrineHydration\Adapters;

use Doctrine\ORM\EntityManagerInterface;
use Nettrine\DoctrineHydration\Arguments\FieldArgs;
use Nettrine\DoctrineHydration\Helpers\RecursiveHydration;

class TargetEntityFieldAdapter implements IFieldAdapter
{

	/** @var EntityManagerInterface */
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function isWorkable(FieldArgs $args): bool
	{
		return isset($args->metadata->getMapping($args->field)['targetEntity']);
	}

	public function work(FieldArgs $args): void
	{
		$targetEntity = $args->metadata->getMapping($args->field)['targetEntity'];

		if ($args->value instanceof $targetEntity) {
			return;
		}
		if ($args->value === null) {
			return;
		}
		if (is_array($args->value)) {
			// TODO: settings
			$args->value = new RecursiveHydration($targetEntity, $args->value);
			return;
		}

		$args->value = $this->em->getRepository($targetEntity)->find($args->value);
	}

}
