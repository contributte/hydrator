<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class Unit extends \Codeception\Module
{

	public function createEntityManager() {
		$config = Setup::createAnnotationMetadataConfiguration([
			__DIR__ . '/../../entities'
		], true);

		$conn = [
			'driver' => 'pdo_sqlite',
			'path' => __DIR__ . '/../../_data/sqlite.db',
		];

		return EntityManager::create($conn, $config);
	}

}
